<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ZoomAccount;

class ZoomAccountPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, ZoomAccount $zoomAccount): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ZoomAccount $zoomAccount): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ZoomAccount $zoomAccount): bool
    {
        return $user->isAdmin();
    }
}
