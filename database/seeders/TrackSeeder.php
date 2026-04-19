<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Track;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    public function run(): void
    {
        $albums = Album::all();

        $tracksData = [
            'vague' => [
                ['title' => 'Horizon', 'slug' => 'horizon', 'duration' => '3:45', 'sort' => 1],
                ['title' => 'Marée', 'slug' => 'maree', 'duration' => '4:12', 'sort' => 2],
                ['title' => 'Nébuleuse', 'slug' => 'nebuleuse', 'duration' => '5:01', 'sort' => 3],
            ],
            'ame-numerique' => [
                ['title' => 'Pulse', 'slug' => 'pulse', 'duration' => '3:28', 'sort' => 1],
                ['title' => 'Binary Dreams', 'slug' => 'binary-dreams', 'duration' => '4:37', 'sort' => 2],
            ],
            'sablier' => [
                ['title' => 'Grain', 'slug' => 'grain', 'duration' => '2:58', 'sort' => 1],
                ['title' => 'Érosion', 'slug' => 'erosion', 'duration' => '4:15', 'sort' => 2],
            ],
            'void' => [
                ['title' => 'Silence', 'slug' => 'silence', 'duration' => '5:22', 'sort' => 1],
                ['title' => 'Fond Noir', 'slug' => 'fond-noir', 'duration' => '3:44', 'sort' => 2],
            ],
            'fractures' => [
                ['title' => 'Fissure', 'slug' => 'fissure', 'duration' => '4:01', 'sort' => 1],
                ['title' => 'Éclats', 'slug' => 'eclats', 'duration' => '3:33', 'sort' => 2],
            ],
        ];

        foreach ($albums as $album) {
            if (isset($tracksData[$album->slug])) {
                foreach ($tracksData[$album->slug] as $track) {
                    Track::create([
                        'album_id' => $album->id,
                        'title' => $track['title'],
                        'slug' => $track['slug'],
                        'platform' => 'youtube',
                        'media_url' => 'https://www.youtube.com/watch?v=...',
                        'duration' => $track['duration'],
                        'sort' => $track['sort'],
                        'active' => true,
                    ]);
                }
            }
        }
    }
}
