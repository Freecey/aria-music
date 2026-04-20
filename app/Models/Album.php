<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    protected $fillable = ['title', 'slug', 'cover_path', 'year', 'platform', 'media_url', 'description', 'sort', 'active'];

    protected $casts = [
        'active' => 'boolean',
        'year' => 'integer',
        'sort' => 'integer',
    ];

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class)->orderBy('sort');
    }

    public function getCoverUrlAttribute(): ?string
    {
        if ($this->cover_path) {
            return asset('storage/' . $this->cover_path);
        }
        return null;
    }
}
