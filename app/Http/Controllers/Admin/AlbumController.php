<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Track;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    protected $media;

    public function __construct(MediaService $media)
    {
        $this->media = $media;
    }

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

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        $data['sort'] = $data['sort'] ?? Album::max('sort') + 1;
        $data['active'] = $request->has('active');

        // Ensure slug uniqueness
        $baseSlug = $data['slug'];
        $counter = 1;
        while (Album::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $baseSlug . '-' . $counter++;
        }

        // Process cover → WebP, resize, rename
        if ($request->hasFile('cover')) {
            $data['cover_path'] = $this->media->processAndStoreImage(
                $request, 'cover', 'covers', $slug
            );
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
            $baseSlug = Str::slug($data['title']);
            $data['slug'] = $baseSlug;

            // Ensure slug uniqueness excluding current album
            $counter = 1;
            while (
                Album::where('slug', $data['slug'])
                    ->where('id', '!=', $id)
                    ->exists()
            ) {
                $data['slug'] = $baseSlug . '-' . $counter++;
            }
        }
        $data['active'] = $request->has('active');

        // Process new cover → WebP, resize, rename
        if ($request->hasFile('cover')) {
            $baseSlug = $data['slug'] ?? $album->slug;
            $data['cover_path'] = $this->media->processAndStoreImage(
                $request, 'cover', 'covers', $baseSlug
            );
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
