<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Update;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Update::query();

        if (!$request->has('all')) {
            $query->where('visible', true);
        }

        $updates = $query->orderBy('published_at', 'desc')->get();

        return response()->json([
            'data' => $updates,
            'meta' => ['total' => $updates->count()]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'body' => 'required|string',
            'visible' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data['visible'] = $data['visible'] ?? true;
        $data['published_at'] = $data['published_at'] ?? now();

        $update = Update::create($data);

        return response()->json(['data' => $update], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $update = Update::findOrFail($id);

        $data = $request->validate([
            'body' => 'sometimes|string',
            'visible' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $update->update($data);

        return response()->json(['data' => $update]);
    }

    public function destroy(int $id): JsonResponse
    {
        $update = Update::findOrFail($id);
        $update->delete();

        return response()->json(['data' => ['message' => 'Update deleted']]);
    }
}
