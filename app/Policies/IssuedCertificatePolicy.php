<?php

namespace App\Policies;

use App\Models\IssuedCertificate;
use App\Models\User;

class IssuedCertificatePolicy
{
    public function view(User $user, IssuedCertificate $certificate): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isStudent() && $certificate->student_id === $user->id;
    }

    public function download(User $user, IssuedCertificate $certificate): bool
    {
        return $this->view($user, $certificate);
    }
}
