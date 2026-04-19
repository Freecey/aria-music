<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            AdminUserSeeder::class,
            AlbumSeeder::class,
            TrackSeeder::class,
            SocialLinkSeeder::class,
            UpdateSeeder::class,
        ]);
    }
}
