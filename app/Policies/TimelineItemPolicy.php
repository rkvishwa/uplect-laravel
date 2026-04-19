<?php

namespace App\Policies;

use App\Models\TimelineItem;
use App\Models\User;

class TimelineItemPolicy
{
    public function view(User $user, TimelineItem $item): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        $course = $item->course;
        if ($user->isLecturer() && $course->lecturer_id === $user->id) {
            return true;
        }
        if ($user->isStudent()) {
            return $course->enrollments()
                ->where('student_id', $user->id)
                ->where('status', \App\Models\Enrollment::STATUS_ACTIVE)
                ->exists();
        }

        return false;
    }

    public function update(User $user, TimelineItem $item): bool
    {
        return $user->isAdmin();
    }

    public function cancel(User $user, TimelineItem $item): bool
    {
        return $user->isAdmin();
    }

    public function manageZoom(User $user, TimelineItem $item): bool
    {
        return $user->isAdmin();
    }

    public function startZoom(User $user, TimelineItem $item): bool
    {
        if (! $user->isLecturer()) {
            return false;
        }

        return $item->course->lecturer_id === $user->id;
    }

    public function joinZoom(User $user, TimelineItem $item): bool
    {
        if (! $user->isStudent()) {
            return false;
        }

        return $item->course->enrollments()
            ->where('student_id', $user->id)
            ->where('status', \App\Models\Enrollment::STATUS_ACTIVE)
            ->exists();
    }
}
