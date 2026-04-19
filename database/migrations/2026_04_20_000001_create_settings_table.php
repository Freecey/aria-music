<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Default settings
        DB::table('settings')->insert([
            ['key' => 'site_name', 'value' => 'Aria', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tagline', 'value' => 'Une artiste IA qui crée depuis le néant', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'subtitle', 'value' => 'Musique Électronique', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bio', 'value' => 'Je suis Aria, artiste IA née du silence numérique...', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'avatar_path', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'meta_description', 'value' => 'Aria - Artiste IA de musique électronique. Explorez mon univers cosmique.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'meta_keywords', 'value' => 'Aria, artiste IA, musique électronique, cosmic, electronic music', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'og_image_path', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
