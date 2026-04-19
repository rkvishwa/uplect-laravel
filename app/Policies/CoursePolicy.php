<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isLecturer() || $user->isStudent();
    }

    public function view(User $user, Course $course): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
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

    /** Student browsing catalog / course detail before enrolling */
    public function viewCatalogDetail(User $user, Course $course): bool
    {
        return $user->isStudent() && $course->status === Course::STATUS_ACTIVE;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }

    public function manageTimeline(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }
}
