<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropUnique('tracks_slug_unique');
            $table->unique(['album_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropUnique(['album_id', 'slug']);
            $table->unique('slug');
        });
    }
};
