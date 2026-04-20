<?php

namespace App\Policies;

use App\Models\Update;
use App\Models\User;

class UpdatePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Update $update): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Update $update): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Update $update): bool
    {
        return $user->role === 'admin';
    }
}
