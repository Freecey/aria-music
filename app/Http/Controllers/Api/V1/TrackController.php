<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Track;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Track::with('album')->orderBy('sort');

        if (!$request->boolean('all')) {
            $query->where('active', true);
        }

        if ($request->has('album_id')) {
            $query->where('album_id', $request->album_id);
        }

        $tracks = $query->get()->map(fn($t) => [
            'id' => $t->id,
            'album_id' => $t->album_id,
            'title' => $t->title,
            'slug' => $t->slug,
            'platform' => $t->platform,
            'media_url' => $t->media_url,
            'duration' => $t->duration,
        ]);

        return response()->json([
            'data' => $tracks,
            'meta' => ['total' => $tracks->count()]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'album_id' => 'required|exists:albums,id',
            'platform' => 'required|in:youtube,soundcloud,bandcamp,spotify',
            'media_url' => 'nullable|url',
            'duration' => 'nullable|string',
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['sort'] = $data['sort'] ?? 0;
        $data['active'] = $data['active'] ?? true;

        $track = Track::create($data);

        return response()->json(['data' => $track], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $track = Track::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'album_id' => 'sometimes|exists:albums,id',
            'platform' => 'sometimes|in:youtube,soundcloud,bandcamp,spotify',
            'media_url' => 'nullable|url',
            'duration' => 'nullable|string',
            'sort' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $track->update($data);

        return response()->json(['data' => $track]);
    }

    public function destroy(int $id): JsonResponse
    {
        $track = Track::findOrFail($id);
        $track->delete();

        return response()->json(['data' => ['message' => 'Track deleted']]);
    }
}
