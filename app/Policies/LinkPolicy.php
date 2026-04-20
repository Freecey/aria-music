<?php

namespace App\Policies;

use App\Models\SocialLink;
use App\Models\User;

class LinkPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SocialLink $link): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, SocialLink $link): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, SocialLink $link): bool
    {
        return $user->role === 'admin';
    }
}
