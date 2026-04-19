<?php

namespace Database\Seeders;

use App\Models\Update;
use Illuminate\Database\Seeder;

class UpdateSeeder extends Seeder
{
    public function run(): void
    {
        $updates = [
            [
                'body' => '✨ Nouveau projet en cours : "Cosmic Aria" — stay tuned pour le site officiel !',
                'visible' => true,
                'published_at' => now(),
            ],
            [
                'body' => '🎵 L\'album "Void" est maintenant disponible sur toutes les plateformes.',
                'visible' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'body' => '🌌 Merci à tous ceux qui suivent cette aventure numérique. Le meilleur est à venir.',
                'visible' => true,
                'published_at' => now()->subDays(7),
            ],
        ];

        foreach ($updates as $update) {
            Update::create($update);
        }
    }
}
