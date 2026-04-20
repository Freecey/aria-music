<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Track;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function index(Request $request)
    {
        $albumId = $request->get('album_id');
        $albums = Album::orderBy('title')->get();
        $tracks = Track::with('album')
            ->when($albumId, fn($q) => $q->where('album_id', $albumId))
            ->orderBy('album_id')
            ->orderBy('sort')
            ->paginate(20);
        return view('admin.tracks.index', compact('tracks', 'albums', 'albumId'));
    }

    public function create(Request $request)
    {
        $albums = Album::where('active', true)->orderBy('title')->get();
        $albumId = $request->get('album_id');
        return view('admin.tracks.create', compact('albums', 'albumId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'album_id' => 'required|exists:albums,id',
            'title' => 'required|string|max:255',
            'platform' => 'required|in:youtube,soundcloud,bandcamp,spotify',
            'media_url' => 'nullable|url',
            'duration' => 'nullable|string|max:20',
            'sort'     => 'nullable|integer',
        ]);

        $data['slug'] = Track::generateSlug($data['title'], $data['album_id']);
        $data['sort'] = $data['sort'] ?? Track::where('album_id', $data['album_id'])->max('sort') + 1;
        $data['active'] = $request->has('active');

        Track::create($data);

        return redirect("/admin/tracks?album_id={$data['album_id']}")->with('success', 'Track créée.');
    }

    public function edit(int $id)
    {
        $track = Track::findOrFail($id);
        $albums = Album::orderBy('title')->get();
        return view('admin.tracks.edit', compact('track', 'albums'));
    }

    public function update(Request $request, int $id)
    {
        $track = Track::findOrFail($id);

        $data = $request->validate([
            'album_id' => 'required|exists:albums,id',
            'title' => 'sometimes|string|max:255',
            'platform' => 'sometimes|in:youtube,soundcloud,bandcamp,spotify',
            'media_url' => 'nullable|url',
            'duration' => 'nullable|string|max:20',
            'sort'     => 'nullable|integer',
        ]);

        if (isset($data['title'])) {
            $albumId = $data['album_id'] ?? $track->album_id;
            $data['slug'] = Track::generateSlug($data['title'], $albumId, $track->id);
        }
        $data['active'] = $request->has('active');

        $track->update($data);

        return redirect("/admin/tracks?album_id={$track->album_id}")->with('success', 'Track mise à jour.');
    }

    public function destroy(int $id)
    {
        $track = Track::findOrFail($id);
        $albumId = $track->album_id;
        $track->delete();
        return redirect("/admin/tracks?album_id={$albumId}")->with('success', 'Track supprimée.');
    }
}
