<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaService
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Process and store an image as WebP.
     * - Converts to WebP
     * - Resizes if > 1920px
     * - Names: {slug}-{timestamp}.webp
     *
     * @param Request $request
     * @param string $inputKey     Form field name
     * @param string $folder       covers | avatars | og
     * @param string|null $baseSlug Override slug (e.g. album slug)
     * @return string|null         Relative path stored in DB (e.g. covers/vague-1714000000.webp)
     */
    public function processAndStoreImage(Request $request, string $inputKey, string $folder, ?string $baseSlug = null): ?string
    {
        if (!$request->hasFile($inputKey)) {
            return null;
        }

        $file = $request->file($inputKey);

        // Build slug from original filename or provided base
        $name = $baseSlug
            ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = Str::slug($name);
        $filename = $slug . '-' . time() . '.webp';

        // Process: resize if > 1920px, convert to WebP
        $image = $this->manager->read($file->getPathname());

        if ($image->width() > 1920) {
            $image->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $path = "{$folder}/{$filename}";
        Storage::disk('public')->put($path, $image->toWebp(85));

        return $path;
    }

    /**
     * Delete a media file from storage.
     *
     * @param string|null $relativePath  Relative path from storage/app/public/
     * @return bool
     */
    public function deleteImage(?string $relativePath): bool
    {
        if (!$relativePath) {
            return false;
        }

        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->delete($relativePath);
        }

        return false;
    }

    /**
     * Get the public URL for a stored image path.
     *
     * @param string|null $relativePath
     * @return string|null
     */
    public function url(?string $relativePath): ?string
    {
        if (!$relativePath) {
            return null;
        }

        return asset('storage/' . $relativePath);
    }
}
