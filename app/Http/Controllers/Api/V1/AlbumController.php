<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Album::with('tracks')->where('active', true)->orderBy('sort');

        if ($request->has('all')) {
            $query = Album::with('tracks')->orderBy('sort');
        }

        $albums = $query->get()->map(function ($album) {
            return [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'cover_url' => $album->cover_url,
                'year' => $album->year,
                'platform' => $album->platform,
                'media_url' => $album->media_url,
                'description' => $album->description,
                'tracks' => $album->tracks->map(fn($t) => [
                    'id' => $t->id,
                    'title' => $t->title,
                    'slug' => $t->slug,
                    'platform' => $t->platform,
                    'media_url' => $t->media_url,
                    'duration' => $t->duration,
                ]),
            ];
        });

        return response()->json([
            'data' => $albums,
            'meta' => ['total' => $albums->count()]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'required|integer',
            'platform' => 'required|in:youtube,soundcloud,bandcamp,spotify',
            'media_url' => 'nullable|url',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:4096',
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['sort'] = $data['sort'] ?? 0;
        $data['active'] = $data['active'] ?? true;

        // Ensure slug uniqueness
        $baseSlug = $data['slug'];
        $counter = 1;
        while (Album::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $baseSlug . '-' . $counter++;
        }

        // Handle cover upload
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = $data['slug'] . '-' . time() . '.webp';
            $path = $file->storeAs('covers', $filename, 'public');
            $data['cover_path'] = $path;
        }

        $album = Album::create($data);

        return response()->json(['data' => $album], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $album = Album::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'year' => 'sometimes|integer',
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

        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = ($data['slug'] ?? $album->slug) . '-' . time() . '.webp';
            $path = $file->storeAs('covers', $filename, 'public');
            $data['cover_path'] = $path;
        }

        $album->update($data);

        return response()->json(['data' => $album]);
    }

    public function destroy(int $id): JsonResponse
    {
        $album = Album::findOrFail($id);
        $album->delete();

        return response()->json(['data' => ['message' => 'Album deleted']]);
    }
}
