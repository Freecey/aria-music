<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanupSeeder extends Seeder
{
    /**
     * Reset test data in production.
     * Does NOT touch: settings, admin users.
     * Run: php artisan db:seed --class=CleanupSeeder
     */
    public function run(): void
    {
        // Truncate (or delete) test data — reset auto-increment counters
        DB::table('tracks')->delete();
        DB::table('albums')->delete();
        DB::table('social_links')->delete();
        DB::table('updates')->delete();
        DB::table('api_logs')->delete();

        // Reset auto-increment IDs so IDs start fresh
        DB::statement('DELETE FROM sqlite_sequence WHERE name IN ("tracks", "albums", "social_links", "updates", "api_logs")');

        $this->command->info('✅ CleanupSeeder — albums, tracks, social_links, updates, api_logs reset.');
    }
}
