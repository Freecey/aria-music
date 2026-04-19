<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'aria@aria-music.be'],
            [
                'name' => 'Aria',
                'email' => 'aria@aria-music.be',
                'password' => Hash::make('aria-secret-2026'),
                'role' => 'admin',
            ]
        );
    }
}
