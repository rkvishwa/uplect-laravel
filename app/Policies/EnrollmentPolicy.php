<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isStudent();
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isStudent() && $enrollment->student_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isStudent();
    }

    public function approve(User $user, Enrollment $enrollment): bool
    {
        return $user->isAdmin();
    }

    public function downloadSlip(User $user, Enrollment $enrollment): bool
    {
        return $user->isAdmin();
    }
}
