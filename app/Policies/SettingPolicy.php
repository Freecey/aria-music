<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Setting $setting): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Setting $setting): bool
    {
        return $user->role === 'admin';
    }

    public function patchBio(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function patchSettings(User $user): bool
    {
        return $user->role === 'admin';
    }
}
