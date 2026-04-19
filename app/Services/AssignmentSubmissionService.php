<?php

namespace App\Services;

use App\Models\AssignmentSubmission;
use App\Models\CourseAssignment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AssignmentSubmissionService
{
    public function __construct(
        protected CertificateEligibilityService $certificateEligibility
    ) {}

    public function submitOrUpdate(
        User $student,
        CourseAssignment $assignment,
        ?string $text,
        ?UploadedFile $file
    ): AssignmentSubmission {
        $assignment->loadMissing('timelineItem');

        $submission = AssignmentSubmission::query()->firstOrNew([
            'course_assignment_id' => $assignment->id,
            'student_id' => $student->id,
        ]);

        if ($submission->exists && $submission->status === AssignmentSubmission::STATUS_GRADED) {
            throw new \RuntimeException(__('This assignment is already graded.'));
        }

        if ($assignment->submission_type === CourseAssignment::SUBMISSION_FILE) {
            if ($file) {
                $dir = 'submissions/'.$assignment->timelineItem->course_id.'/'.$assignment->id;
                if ($submission->submission_file_path) {
                    Storage::disk('local')->delete($submission->submission_file_path);
                }
                $submission->submission_file_path = $file->store($dir, 'local');
            }
            $submission->submission_text = null;
        } else {
            $submission->submission_text = $text;
            if ($submission->submission_file_path) {
                Storage::disk('local')->delete($submission->submission_file_path);
                $submission->submission_file_path = null;
            }
        }

        $submission->submitted_at = now();
        $submission->status = AssignmentSubmission::STATUS_SUBMITTED;
        $submission->save();

        return $submission;
    }

    public function grade(AssignmentSubmission $submission, User $lecturer, float $marks, ?string $feedback): void
    {
        $submission->load('assignment.timelineItem');

        $submission->update([
            'marks' => $marks,
            'graded_by' => $lecturer->id,
            'graded_at' => now(),
            'feedback' => $feedback,
            'status' => AssignmentSubmission::STATUS_GRADED,
        ]);

        $courseId = $submission->assignment->timelineItem->course_id;
        $this->certificateEligibility->evaluateAndIssue($submission->student, $courseId);
    }

    public function returnToStudent(AssignmentSubmission $submission, User $lecturer): void
    {
        $submission->update([
            'status' => AssignmentSubmission::STATUS_RETURNED,
            'graded_by' => $lecturer->id,
            'graded_at' => now(),
        ]);
    }
}
