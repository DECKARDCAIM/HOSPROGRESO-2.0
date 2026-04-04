<?php

namespace App\Http\Controllers;

use App\Events\ReleaseCreated;
use App\Http\Requests\StoreReleaseRequest;
use App\Http\Requests\UpdateReleaseRequest;
use App\Models\Release;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReleaseController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'releases_index_'.md5(json_encode($request->all()));

        $data = Cache::tags(['releases'])->remember($cacheKey, now()->addHours(2), function () use ($request) {
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
                    $q->where('title', 'like', '%'.$request->search.'%')
                        ->orWhere('content', 'like', '%'.$request->search.'%');
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('status') && in_array($request->status, ['draft', 'published'])) {
                $query->where('status', $request->status);
            }

            $query->orderBy('published_at', 'desc');

            $allFilteredIds = $query->pluck('id')->toArray();
            $perPage = $request->input('per_page', 5);
            $releases = $query->paginate($perPage)->appends($request->query());

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

            $calendarData = Release::published()
                ->select('id', 'title', 'published_at')
                ->get()
                ->groupBy(fn ($item) => $item->published_at->format('Y-m-d'))
                ->map(fn ($dayReleases) => [
                    'count' => $dayReleases->count(),
                    'titles' => $dayReleases->take(3)->pluck('title')->toArray(),
                    'has_more' => $dayReleases->count() > 3,
                ]);

            return compact(
                'releases', 'calendarData', 'allTotal', 'allPublished',
                'allDrafts', 'allThisMonth', 'activeFilters', 'allFilteredIds'
            );
        });

        return view('modules.administration.release.index', $data);
    }

    public function create()
    {
        return view('modules.administration.release.create');
    }

    public function store(StoreReleaseRequest $request)
    {
        $validated = $request->validated();

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'type' => $validated['type'],
            'author_id' => auth()->id(),
            'published_at' => $request->filled('published_at')
                                ? Carbon::parse($validated['published_at'])
                                : ($validated['status'] == 'published' ? now() : null),
        ];

        if ($request->hasFile('documents')) {
            $paths = [];
            foreach ($request->file('documents') as $file) {
                $fileName = Str::slug($validated['title']).'-'.time().'-'.uniqid().'.'.$file->getClientOriginalExtension();
                $paths[] = $file->storeAs('releases', $fileName, 'public');
            }
            $data['document_path'] = $paths;
        }

        if ($request->hasFile('background_image')) {
            $bgName = 'bg-'.time().'-'.uniqid().'.'.$request->file('background_image')->getClientOriginalExtension();
            $data['background_image'] = $request->file('background_image')->storeAs('releases/backgrounds', $bgName, 'public');
        }

        $release = Release::create($data);

        if ($release->status === 'published') {
            try {
                $mensajeToast = 'Se ha publicado un nuevo comunicado: '.$release->title;
                broadcast(new ReleaseCreated('Nuevo Comunicado', $mensajeToast, 'info'));
            } catch (\Exception $e) {
                Log::error('Fallo en la transmisión de comunicado (Reverb): '.$e->getMessage());
            }
        }

        return redirect()->route('releases.index')->with('success', 'Comunicado creado exitosamente.');
    }

    public function show($id)
    {
        $release = Release::withTrashed()->findOrFail($id);

        return view('modules.administration.release.show', compact('release'));
    }

    public function edit(Release $release)
    {
        return view('modules.administration.release.edit', compact('release'));
    }

    public function update(UpdateReleaseRequest $request, Release $release)
    {
        $validated = $request->validated();

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'type' => $validated['type'],
            'published_at' => $request->filled('published_at')
                                ? Carbon::parse($validated['published_at'])
                                : (($validated['status'] == 'published' && ! $release->published_at) ? now() : $release->published_at),
        ];

        if ($request->hasFile('documents')) {
            if ($release->document_path && is_array($release->document_path)) {
                foreach ($release->document_path as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $paths = [];
            foreach ($request->file('documents') as $file) {
                $fileName = Str::slug($validated['title']).'-'.time().'-'.uniqid().'.'.$file->getClientOriginalExtension();
                $paths[] = $file->storeAs('releases', $fileName, 'public');
            }
            $data['document_path'] = $paths;
        }

        if ($request->hasFile('background_image')) {
            if ($release->background_image) {
                Storage::disk('public')->delete($release->background_image);
            }
            $bgName = 'bg-'.time().'-'.uniqid().'.'.$request->file('background_image')->getClientOriginalExtension();
            $data['background_image'] = $request->file('background_image')->storeAs('releases/backgrounds', $bgName, 'public');
        }

        $wasDraft = $release->status === 'draft';

        $release->update($data);

        if ($wasDraft && $release->status === 'published') {
            try {
                broadcast(new ReleaseCreated('Nuevo Comunicado', 'Se ha publicado un nuevo comunicado: '.$release->title, 'info'));
            } catch (\Exception $e) {
                Log::error('Fallo en la transmisión de comunicado (Reverb): '.$e->getMessage());
            }
        }

        return redirect()->route('releases.index')->with('success', 'Comunicado actualizado exitosamente.');
    }

    public function destroy(Release $release)
    {
        $release->delete();

        return redirect()->route('releases.index')->with('success', 'Comunicado eliminado exitosamente.');
    }

    public function markAsRead(Request $request, $id)
    {
        $user = auth()->user();
        $user->readReleases()->syncWithoutDetaching([$id => ['read_at' => now()]]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = auth()->user();
        $unreadReleases = Release::published()
            ->where('published_at', '<=', now())
            ->whereDoesntHave('readByUsers', fn ($query) => $query->where('user_id', $user->id))
            ->get();

        foreach ($unreadReleases as $release) {
            $user->readReleases()->syncWithoutDetaching([$release->id => ['read_at' => now()]]);
        }

        return response()->json(['success' => true]);
    }

    public function getUnread(Request $request)
    {
        $user = auth()->user();
        $since = $request->input('since') ? Carbon::parse($request->input('since')) : now()->subMinutes(1);

        $unread = Release::published()
            ->where('published_at', '>', $since)
            ->where('published_at', '<=', now())
            ->whereDoesntHave('readByUsers', fn ($query) => $query->where('user_id', $user->id))
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
                'published_at' => now()->toDateTimeString(),
            ];
        }

        return response()->json([
            'notifications' => $notifications,
            'server_time' => now()->toDateTimeString(),
        ]);
    }

    public function generateAiContent(Request $request): JsonResponse
    {
        $validated = $request->validate(['prompt' => 'required|string|max:1000']);

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
                    ['role' => 'user', 'content' => "Por favor, redacta el comunicado basándote en lo siguiente:\n".$validated['prompt']],
                ],
                'stream' => false,
                'options' => ['temperature' => 0.4],
            ]);

            if ($response->successful()) {
                $rawContent = $response->json('message.content', 'Error al generar contenido.');

                $rawContent = preg_replace('/^```json/i', '', $rawContent);
                $rawContent = preg_replace('/^```/i', '', $rawContent);
                $rawContent = preg_replace('/```$/i', '', $rawContent);
                $rawContent = trim($rawContent);

                $dataDecoded = json_decode($rawContent, true);

                if (json_last_error() === JSON_ERROR_NONE && isset($dataDecoded['html']) && isset($dataDecoded['title'])) {
                    return response()->json([
                        'success' => true,
                        'title' => $dataDecoded['title'],
                        'html' => $dataDecoded['html'],
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'title' => 'Comunicado Generado por ISAAC',
                        'html' => $rawContent,
                    ]);
                }
            }

            Log::error("Error de Ollama API al generar comunicado: {$response->body()}");

            return response()->json(['success' => false, 'message' => "Lo siento {$userName}, no pude generar el contenido. Código: {$response->status()}"], 500);

        } catch (\Exception $e) {
            Log::error("Excepción al conectar con Ollama en generador de comunicados: {$e->getMessage()}");

            return response()->json(['success' => false, 'message' => 'Error de conexión con ISAAC. Verifica que el servicio esté activo.'], 500);
        }
    }
}
