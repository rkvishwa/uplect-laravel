<?php

namespace App\Services;

use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\CourseCertificate;
use App\Models\IssuedCertificate;
use App\Models\TimelineItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CertificateEligibilityService
{
    public function __construct(
        protected CertificateGeneratorService $generator
    ) {}

    public function evaluateAndIssue(User $student, int $courseId): void
    {
        DB::transaction(function () use ($student, $courseId) {
            if (IssuedCertificate::query()->where('course_id', $courseId)->where('student_id', $student->id)->exists()) {
                return;
            }

            $course = Course::query()->find($courseId);
            if ($course === null) {
                return;
            }

            $hasMilestone = TimelineItem::query()
                ->where('course_id', $courseId)
                ->where('cardable_type', CourseCertificate::class)
                ->whereDate('scheduled_date', '<=', now()->toDateString())
                ->exists();

            if (! $hasMilestone) {
                return;
            }

            if (! $this->passesRequiredAssignments($student, $courseId)) {
                return;
            }

            $this->generator->generate($student, $course);
        });
    }

    protected function passesRequiredAssignments(User $student, int $courseId): bool
    {
        $items = TimelineItem::query()
            ->where('course_id', $courseId)
            ->where('cardable_type', CourseAssignment::class)
            ->with('cardable')
            ->get();

        foreach ($items as $item) {
            $assignment = $item->cardable;
            if (! $assignment instanceof CourseAssignment || ! $assignment->is_required_for_certification) {
                continue;
            }

            $sub = AssignmentSubmission::query()
                ->where('course_assignment_id', $assignment->id)
                ->where('student_id', $student->id)
                ->first();

            if ($sub === null
                || $sub->status !== AssignmentSubmission::STATUS_GRADED
                || $sub->marks === null
                || (float) $sub->marks < (float) $assignment->pass_mark) {
                return false;
            }
        }

        return true;
    }
}
