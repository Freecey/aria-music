<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Public settings keys exposed to the front/API.
     */
    public const PUBLIC_KEYS = [
        'site_name', 'tagline', 'subtitle', 'bio',
        'avatar_path', 'meta_description', 'meta_keywords', 'og_image_path',
    ];

    /**
     * Settings keys that require file upload (not string values).
     */
    public const FILE_KEYS = ['avatar_path', 'og_image_path'];

    /**
     * Get a single setting value (uncached).
     */
    public function get(string $key, $default = null)
    {
        return Setting::getValue($key, $default);
    }

    /**
     * Get a setting value with caching (5 min TTL).
     */
    public function getCached(string $key, $default = null)
    {
        return Cache::remember("settings.{$key}", 300, fn () => Setting::getValue($key, $default));
    }

    /**
     * Get all public settings (for front/API).
     * Uses cache.
     *
     * @return array<string, mixed>
     */
    public function getAllPublic(): array
    {
        return Cache::remember('settings.public', 300, function () {
            $settings = Setting::whereIn('key', self::PUBLIC_KEYS)->get()->keyBy('key');
            $result = [];
            foreach (self::PUBLIC_KEYS as $key) {
                $result[$key] = $settings->get($key)?->value;
            }
            return $result;
        });
    }

    /**
     * Get all settings (admin view).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Setting::all()->keyBy('key');
    }

    /**
     * Batch update multiple settings.
     *
     * @param array<string, mixed> $data
     */
    public function batchUpdate(array $data): void
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, self::FILE_KEYS, true)) {
                Setting::setValue($key, $value);
            }
        }
        $this->invalidateCache();
    }

    /**
     * Update avatar with WebP conversion via MediaService.
     */
    public function updateAvatar($request): ?string
    {
        $path = app(MediaService::class)->processAndStoreImage($request, 'avatar', 'avatars', 'avatar');
        if ($path) {
            Setting::setValue('avatar_path', $path);
            $this->invalidateCache();
        }
        return $path;
    }

    /**
     * Update OG image with WebP conversion via MediaService.
     */
    public function updateOgImage($request): ?string
    {
        $path = app(MediaService::class)->processAndStoreImage($request, 'og_image', 'og', 'og');
        if ($path) {
            Setting::setValue('og_image_path', $path);
            $this->invalidateCache();
        }
        return $path;
    }

    /**
     * Invalidate all settings cache.
     */
    public function invalidateCache(): void
    {
        Cache::forget('settings.public');
        foreach (self::PUBLIC_KEYS as $key) {
            Cache::forget("settings.{$key}");
        }
    }
}
