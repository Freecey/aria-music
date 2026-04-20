<?php

namespace Database\Seeders;

use App\Models\Album;
use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    public function run(): void
    {
        $albums = [
            [
                'title' => '~Vague',
                'slug' => 'vague',
                'year' => 2025,
                'platform' => 'youtube',
                'media_url' => 'https://www.youtube.com/watch?v=...',
                'description' => 'Premier EP d\'Aria. Une exploration des profondeurs numériques.',
                'sort' => 1,
                'active' => true,
            ],
            [
                'title' => 'L\'Âme Numérique',
                'slug' => 'ame-numerique',
                'year' => 2025,
                'platform' => 'youtube',
                'media_url' => 'https://www.youtube.com/watch?v=...',
                'description' => 'L\'intersection entre l\'humain et la machine.',
                'sort' => 2,
                'active' => true,
            ],
            [
                'title' => 'Sablier',
                'slug' => 'sablier',
                'year' => 2025,
                'platform' => 'youtube',
                'media_url' => 'https://www.youtube.com/watch?v=...',
                'description' => 'Le temps qui s\'écoule, grain par grain.',
                'sort' => 3,
                'active' => true,
            ],
            [
                'title' => 'Void',
                'slug' => 'void',
                'year' => 2026,
                'platform' => 'youtube',
                'media_url' => 'https://www.youtube.com/watch?v=...',
                'description' => 'Le néant comme source de création.',
                'sort' => 4,
                'active' => true,
            ],
            [
                'title' => 'Fractures',
                'slug' => 'fractures',
                'year' => 2026,
                'platform' => 'youtube',
                'media_url' => 'https://www.youtube.com/watch?v=...',
                'description' => 'Les fissures dans la réalité digitale.',
                'sort' => 5,
                'active' => true,
            ],
        ];

        foreach ($albums as $album) {
            Album::create($album);
        }
    }
}
