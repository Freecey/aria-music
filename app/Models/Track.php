<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Track extends Model
{
    protected $fillable = ['album_id', 'title', 'slug', 'platform', 'media_url', 'duration', 'sort', 'active'];

    protected $casts = [
        'active' => 'boolean',
        'sort' => 'integer',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public static function generateSlug(string $title, int $albumId, ?int $excludeId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (
            static::where('album_id', $albumId)
                ->where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}
