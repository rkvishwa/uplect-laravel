<?php

namespace App\Policies;

use App\Models\CourseAssignment;
use App\Models\User;

class CourseAssignmentPolicy
{
    public function update(User $user, CourseAssignment $assignment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        if (! $user->isLecturer()) {
            return false;
        }

        return $assignment->timelineItem->course->lecturer_id === $user->id;
    }

    public function grade(User $user, CourseAssignment $assignment): bool
    {
        if (! $user->isLecturer()) {
            return false;
        }

        return $assignment->timelineItem->course->lecturer_id === $user->id;
    }
}
