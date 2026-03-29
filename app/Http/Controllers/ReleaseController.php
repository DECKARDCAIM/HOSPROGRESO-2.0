<?php

namespace App\Http\Controllers;

use App\Models\Release;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReleaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Release::with('author');

        if ($request->input('record_status', 'active') === 'inactive') {
            $query->onlyTrashed();
        }

        if ($request->filled('date_from') || $request->filled('date_to')) {
            if ($request->filled('date_from')) {
                $query->whereDate('published_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('published_at', '<=', $request->date_to);
            }
        } else {
            $query->whereDate('published_at', now()->toDateString());
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && in_array($request->status, ['draft', 'published', 'archived'])) {
            $query->where('status', $request->status);
        }

        $query->orderBy('published_at', 'desc');

        $allFilteredIds = $query->pluck('id')->toArray();
        $perPage = $request->input('per_page', 5);
        $releases = $query->paginate($perPage);

        // Metrics for the cards
        $allTotal = Release::count();
        $allPublished = Release::published()->count();
        $allDrafts = Release::where('status', 'draft')->count();
        $allThisMonth = Release::whereMonth('created_at', now()->month)->count();

        $activeFilters = 0;
        if ($request->filled('status')) {
            $activeFilters++;
        }
        if ($request->filled('type')) {
            $activeFilters++;
        }
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $activeFilters++;
        }

        // For the calendar: get counts and titles of releases per day
        $calendarData = Release::published()
            ->select('id', 'title', 'published_at')
            ->get()
            ->groupBy(function ($item) {
                return $item->published_at->format('Y-m-d');
            })
            ->map(function ($dayReleases) {
                return [
                    'count' => $dayReleases->count(),
                    'titles' => $dayReleases->take(3)->pluck('title')->toArray(),
                    'has_more' => $dayReleases->count() > 3
                ];
            });

        return view('modules.administration.release.index', compact(
            'releases', 
            'calendarData', 
            'allTotal', 
            'allPublished', 
            'allDrafts', 
            'allThisMonth',
            'activeFilters',
            'allFilteredIds'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.administration.release.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'type' => 'required|in:actualizacion,comunicado',
            'published_at' => 'nullable|date',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:51200',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'type' => $request->type,
            'author_id' => auth()->id(),
            'published_at' => $request->published_at ? \Carbon\Carbon::parse($request->published_at) : ( $request->status == 'published' ? now() : null ),
        ];

        if ($request->hasFile('documents')) {
            $paths = [];
            foreach ($request->file('documents') as $file) {
                $fileName = Str::slug($request->title) . '-' . time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $paths[] = $file->storeAs('releases', $fileName, 'public');
            }
            $data['document_path'] = $paths;
        }

        if ($request->hasFile('background_image')) {
            $bgName = 'bg-' . time() . '-' . uniqid() . '.' . $request->file('background_image')->getClientOriginalExtension();
            $data['background_image'] = $request->file('background_image')->storeAs('releases/backgrounds', $bgName, 'public');
        }

        Release::create($data);

        return redirect()->route('releases.index')->with('success', 'Comunicado creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $release = Release::withTrashed()->findOrFail($id);
        return view('modules.administration.release.show', compact('release'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Release $release)
    {
        return view('modules.administration.release.edit', compact('release'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Release $release)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'type' => 'required|in:actualizacion,comunicado',
            'published_at' => 'nullable|date',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:51200',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'type' => $request->type,
            'published_at' => $request->published_at ? \Carbon\Carbon::parse($request->published_at) : ( ($request->status == 'published' && !$release->published_at) ? now() : $release->published_at ),
        ];

        if ($request->hasFile('documents')) {
            // Delete old files if they exist
            if ($release->document_path && is_array($release->document_path)) {
                foreach ($release->document_path as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            $paths = [];
            foreach ($request->file('documents') as $file) {
                $fileName = Str::slug($request->title) . '-' . time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $paths[] = $file->storeAs('releases', $fileName, 'public');
            }
            $data['document_path'] = $paths;
        }

        if ($request->hasFile('background_image')) {
            if ($release->background_image) {
                Storage::disk('public')->delete($release->background_image);
            }
            $bgName = 'bg-' . time() . '-' . uniqid() . '.' . $request->file('background_image')->getClientOriginalExtension();
            $data['background_image'] = $request->file('background_image')->storeAs('releases/backgrounds', $bgName, 'public');
        }

        $release->update($data);

        return redirect()->route('releases.index')->with('success', 'Comunicado actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Release $release)
    {
        $release->delete();
        return redirect()->route('releases.index')->with('success', 'Comunicado eliminado exitosamente.');
    }

    /**
     * Mark a release as read for the current user.
     */
    public function markAsRead(Request $request, $id)
    {
        $user = auth()->user();
        $user->readReleases()->syncWithoutDetaching([
            $id => ['read_at' => now()]
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all releases as read for the current user.
     */
    public function markAllAsRead()
    {
        $user = auth()->user();
        $unreadReleases = Release::published()
            ->where('published_at', '<=', now())
            ->whereDoesntHave('readByUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        foreach ($unreadReleases as $release) {
            $user->readReleases()->syncWithoutDetaching([
                $release->id => ['read_at' => now()]
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get unread notifications since a specific time.
     */
    public function getUnread(Request $request)
    {
        $user = auth()->user();
        $sinceParam = $request->input('since');
        $since = $sinceParam ? \Carbon\Carbon::parse($sinceParam) : now()->subMinutes(1);

        $unread = Release::published()
            ->where('published_at', '>', $since)
            ->where('published_at', '<=', now())
            ->whereDoesntHave('readByUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();

        $notifications = [];
        if ($unread > 0) {
            $notifications[] = [
                'id' => 'unread-notif',
                'title' => 'Nuevos Comunicados',
                'message' => 'Tienes mensajes nuevos sin leer en tu bandeja. Por favor, revísalos.',
                'type' => 'info',
                'author_name' => 'Sistema',
                'author_avatar' => asset('img/logo.png'),
                'time_ago' => 'Justo ahora',
                'published_at' => now()->toDateTimeString()
            ];
        }

        return response()->json([
            'notifications' => $notifications,
            'server_time' => now()->toDateTimeString()
        ]);
    }

    /**
     * Generar contenido usando ISAAC (IA del sistema).
     */
    public function generateAiContent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => 'required|string|max:1000'
        ]);

        $user = auth()->user();
        $userName = $user ? trim("{$user->first_name} {$user->first_last_name}") : 'Usuario';

        $baseUrl = rtrim(config('services.ollama.url'), '/');
        $model = config('services.ollama.model');

        $systemPrompt = <<<EOT
        Eres ISAAC, la Inteligencia Artificial del sistema HOSPROGRESO.
        Estás hablando con tu usuario autenticado actual: {$userName}.
        Tu tarea en este momento es actuar como un redactor experto para el sistema. 
        Debes ayudar al usuario a redactar el contenido de un Comunicado Oficial o Actualización.
        
        IMPORTANTE: Tu respuesta estructurada debe ser estrictamente un objeto JSON válido, sin ningún texto adicional antes o después. 
        El JSON debe tener exactamente esta estructura:
        {
            "title": "El título generado para el comunicado",
            "html": "El cuerpo del comunicado en formato HTML (<h1>, <p>, <ul>, etc.)"
        }
        Asegúrate de no incluir las etiquetas <html>, <body> o <style> en la clave "html".
        EOT;

        try {
            $response = Http::timeout(90)->post("{$baseUrl}/api/chat", [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => "Por favor, redacta el comunicado basándote en lo siguiente:\n" . $validated['prompt']]
                ],
                'stream' => false,
                'options' => ['temperature' => 0.4] 
            ]);

            if ($response->successful()) {
                $rawContent = $response->json('message.content', 'Error al generar contenido.');
                
                // Limpiar posibles bloques markdown "```json" y "```"
                $rawContent = preg_replace('/^```json/i', '', $rawContent);
                $rawContent = preg_replace('/^```/i', '', $rawContent);
                $rawContent = preg_replace('/```$/i', '', $rawContent);
                $rawContent = trim($rawContent);
                
                $dataDecoded = json_decode($rawContent, true);
                
                if (json_last_error() === JSON_ERROR_NONE && isset($dataDecoded['html']) && isset($dataDecoded['title'])) {
                    return response()->json([
                        'success' => true, 
                        'title' => $dataDecoded['title'],
                        'html' => $dataDecoded['html']
                    ]);
                } else {
                    // Fallback si no retornó JSON válido
                    return response()->json([
                        'success' => true,
                        'title' => 'Comunicado Generado por ISAAC',
                        'html' => $rawContent
                    ]);
                }
            }

            Log::error("Error de Ollama API al generar comunicado: {$response->body()}");
            
            return response()->json([
                'success' => false,
                'message' => "Lo siento {$userName}, no pude generar el contenido. Código: {$response->status()}"
            ], 500);

        } catch (\Exception $e) {
            Log::error("Excepción al conectar con Ollama en generador de comunicados: {$e->getMessage()}");
            
            return response()->json([
                'success' => false,
                'message' => 'Error de conexión con ISAAC. Verifica que el servicio esté activo.'
            ], 500);
        }
    }
}
