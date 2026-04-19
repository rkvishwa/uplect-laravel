<?php

namespace App\Policies;

use App\Models\AssignmentSubmission;
use App\Models\User;

class AssignmentSubmissionPolicy
{
    public function submit(User $user, AssignmentSubmission $submission): bool
    {
        if (! $user->isStudent()) {
            return false;
        }

        return $submission->student_id === $user->id;
    }

    public function grade(User $user, AssignmentSubmission $submission): bool
    {
        if (! $user->isLecturer()) {
            return false;
        }

        return $submission->assignment->timelineItem->course->lecturer_id === $user->id;
    }
}
