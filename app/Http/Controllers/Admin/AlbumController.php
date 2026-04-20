<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::with('tracks')->orderBy('sort')->get();
        return view('admin.albums.index', compact('albums'));
    }

    public function create()
    {
        return view('admin.albums.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:2100',
            'platform' => 'required|in:youtube,soundcloud,bandcamp,spotify',
            'media_url' => 'nullable|url',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:4096',
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['sort'] = $data['sort'] ?? Album::max('sort') + 1;
        $data['active'] = $request->has('active');

        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = $data['slug'] . '-' . time() . '.webp';
            $path = $file->storeAs('covers', $filename, 'public');
            $data['cover_path'] = $path;
        }

        Album::create($data);

        return redirect('/admin/albums')->with('success', 'Album créé avec succès.');
    }

    public function edit(int $id)
    {
        $album = Album::with('tracks')->findOrFail($id);
        return view('admin.albums.edit', compact('album'));
    }

    public function update(Request $request, int $id)
    {
        $album = Album::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'year' => 'sometimes|integer|min:2000|max:2100',
            'platform' => 'sometimes|in:youtube,soundcloud,bandcamp,spotify',
            'media_url' => 'nullable|url',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:4096',
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['active'] = $request->has('active');

        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = ($data['slug'] ?? $album->slug) . '-' . time() . '.webp';
            $path = $file->storeAs('covers', $filename, 'public');
            $data['cover_path'] = $path;
        }

        $album->update($data);

        return redirect('/admin/albums')->with('success', 'Album mis à jour.');
    }

    public function destroy(int $id)
    {
        $album = Album::findOrFail($id);
        $album->delete();
        return redirect('/admin/albums')->with('success', 'Album supprimé.');
    }
}
