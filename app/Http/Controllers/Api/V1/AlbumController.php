<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    protected $media;

    public function __construct(MediaService $media)
    {
        $this->media = $media;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = min($request->integer('per_page', 20), 100);
        $showAll = $request->boolean('all') && auth('sanctum')->check();

        if ($showAll) {
            $albums = Album::with('tracks')
                ->orderBy('sort')
                ->get()
                ->map(fn($album) => $this->formatAlbum($album));

            return response()->json([
                'data' => $albums,
                'meta' => ['total' => $albums->count()]
            ]);
        }

        $paginator = Album::with('tracks')
            ->where('active', true)
            ->orderBy('sort')
            ->paginate($perPage);

        $albums = $paginator->getCollection()->map(fn($album) => $this->formatAlbum($album));

        return response()->json([
            'data' => $albums,
            'meta' => [
                'total' => $paginator->total(),
                'page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'last_page' => $paginator->lastPage(),
            ]
        ]);
    }

    private function formatAlbum($album): array
    {
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

        // Handle cover upload → WebP conversion via MediaService
        if ($request->hasFile('cover')) {
            $data['cover_path'] = $this->media->processAndStoreImage(
                $request, 'cover', 'covers', $data['slug']
            );
        }

        $album = Album::with('tracks')->find(Album::create($data)->id);

        return response()->json(['data' => $this->formatAlbum($album)], 201);
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
            $baseSlug = $data['slug'] ?? $album->slug;
            $data['cover_path'] = $this->media->processAndStoreImage(
                $request, 'cover', 'covers', $baseSlug
            );
        }

        $album->update($data);
        $album->load('tracks');

        return response()->json(['data' => $this->formatAlbum($album)]);
    }

    public function destroy(int $id): JsonResponse
    {
        $album = Album::findOrFail($id);
        $album->delete();

        return response()->json(['data' => ['message' => 'Album deleted']]);
    }
}
