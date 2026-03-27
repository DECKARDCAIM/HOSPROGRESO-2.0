<?php

namespace App\Http\Controllers;

use App\Models\Release;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReleaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Release::with('author')->latest();

        if ($request->has('date')) {
            $query->whereDate('published_at', $request->date);
        }

        $releases = $query->paginate(25);

        // For the calendar: get counts of releases per day for the current month
        $calendarData = Release::selectRaw('DATE(published_at) as date, COUNT(*) as count')
            ->where('status', 'published')
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date');

        return view('modules.administration.release.index', compact('releases', 'calendarData'));
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
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'type' => $request->type,
            'author_id' => auth()->id(),
            'published_at' => $request->published_at ? \Carbon\Carbon::parse($request->published_at) : ( $request->status == 'published' ? now() : null ),
        ];

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = Str::slug($request->title) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('releases', $fileName, 'public');
            $data['document_path'] = $path;
        }

        Release::create($data);

        return redirect()->route('releases.index')->with('success', 'Comunicado creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Release $release)
    {
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
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'type' => $request->type,
            'published_at' => $request->published_at ? \Carbon\Carbon::parse($request->published_at) : ( ($request->status == 'published' && !$release->published_at) ? now() : $release->published_at ),
        ];

        if ($request->hasFile('document')) {
            // Delete old file
            if ($release->document_path) {
                Storage::disk('public')->delete($release->document_path);
            }
            $file = $request->file('document');
            $fileName = Str::slug($request->title) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('releases', $fileName, 'public');
            $data['document_path'] = $path;
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
            ->with('author')
            ->where('published_at', '>', $since)
            ->whereDoesntHave('readByUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest('published_at')
            ->get();

        return response()->json([
            'notifications' => $unread->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => strip_tags($notif->content),
                    'type' => $notif->type === 'actualizacion' ? 'info' : 'success',
                    'author' => $notif->author ? $notif->author->first_name : 'Sistema',
                    'published_at' => $notif->published_at->toDateTimeString()
                ];
            }),
            'server_time' => now()->toDateTimeString()
        ]);
    }
}
