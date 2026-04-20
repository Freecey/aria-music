<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = SocialLink::query();

        if (!($request->has('all') && auth('sanctum')->check())) {
            $query->where('active', true);
        }

        $links = $query->orderBy('sort')->get();

        return response()->json([
            'data' => $links,
            'meta' => ['total' => $links->count()]
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $link = SocialLink::where('active', true)->findOrFail($id);

        return response()->json(['data' => $link]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'platform' => 'required|string|max:50',
            'label' => 'required|string|max:100',
            'url'      => 'required|string|max:1000',
            'icon_svg' => 'nullable|string|max:5000',
            'sort'     => 'nullable|integer',
            'active'   => 'nullable|boolean',
        ]);

        if (isset($data['icon_svg'])) {
            $data['icon_svg'] = strip_tags($data['icon_svg'], '<svg><path><circle><rect><polygon><polyline><line><g><defs><use><symbol>');
        }

        $data['sort'] = $data['sort'] ?? 0;
        $data['active'] = $data['active'] ?? true;

        $link = SocialLink::create($data);

        return response()->json(['data' => $link], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $link = SocialLink::findOrFail($id);

        $data = $request->validate([
            'platform' => 'sometimes|string|max:50',
            'label' => 'sometimes|string|max:100',
            'url'      => 'sometimes|string|max:1000',
            'icon_svg' => 'nullable|string|max:5000',
            'sort'     => 'nullable|integer',
            'active'   => 'nullable|boolean',
        ]);

        if (isset($data['icon_svg'])) {
            $data['icon_svg'] = strip_tags($data['icon_svg'], '<svg><path><circle><rect><polygon><polyline><line><g><defs><use><symbol>');
        }

        $link->update($data);

        return response()->json(['data' => $link]);
    }

    public function destroy(int $id): JsonResponse
    {
        $link = SocialLink::findOrFail($id);
        $link->delete();

        return response()->json(['data' => ['message' => 'Link deleted']]);
    }
}
