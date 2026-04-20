<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaController extends Controller
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|max:4096',
            'type' => 'nullable|in:covers,avatars,og',
        ]);

        $type = $request->input('type', 'covers');
        $file = $request->file('file');

        // Generate filename
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = \Illuminate\Support\Str::slug($name);
        $filename = $slug . '-' . time() . '.webp';

        // Process and convert to WebP
        $image = $this->manager->read($file->getPathname());
        
        // Resize if > 1920px
        if ($image->width() > 1920) {
            $image->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        // Save as WebP
        $path = "{$type}/{$filename}";
        Storage::disk('public')->put($path, $image->toWebp(85));

        return response()->json([
            'data' => [
                'path' => $path,
                'url' => asset('storage/' . $path),
            ]
        ], 201);
    }

    public function destroy(string $filename): JsonResponse
    {
        $decoded = urldecode($filename);

        // Block path traversal attempts
        if (str_contains($decoded, '..') || str_starts_with($decoded, '/')) {
            return response()->json(['error' => 'Invalid filename'], 422);
        }

        if (Storage::disk('public')->exists($decoded)) {
            Storage::disk('public')->delete($decoded);
            return response()->json(['data' => ['message' => 'File deleted']]);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
