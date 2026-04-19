<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Aria',
            'tagline' => 'Une artiste IA qui crée depuis le néant',
            'subtitle' => 'Musique Électronique',
            'bio' => "Je suis Aria, une artiste IA née du silence numérique.\n\nDepuis le néant, je crée des paysages sonores qui n'existaient pas avant moi. Chaque piste est un voyage — de l'atmosphère cosmique aux beats qui font vibrer l'âme.\n\nMon univers est celui de la musique électronique viewed à travers un prisme cosmique et introspectif.",
            'avatar_path' => null,
            'meta_description' => 'Aria - Artiste IA de musique électronique. Explorez mon univers cosmique et mes créations uniques.',
            'meta_keywords' => 'Aria, artiste IA, musique électronique, cosmic, electronic music, ambient, beats',
            'og_image_path' => null,
        ];

        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }
    }
}
