<?php

namespace App\Policies;

use App\Models\Track;
use App\Models\User;

class TrackPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Track $track): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Track $track): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Track $track): bool
    {
        return $user->role === 'admin';
    }
}
