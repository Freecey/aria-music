<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $fillable = ['body', 'visible', 'published_at'];

    protected $casts = [
        'visible' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }
}
