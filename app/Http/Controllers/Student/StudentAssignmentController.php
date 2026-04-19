<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitAssignmentRequest;
use App\Models\AssignmentSubmission;
use App\Models\CourseAssignment;
use App\Services\AssignmentSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentAssignmentController extends Controller
{
    public function show(CourseAssignment $assignment): View
    {
        $assignment->load('timelineItem.course');
        $course = $assignment->timelineItem->course;
        $this->authorize('view', $course);

        $submission = AssignmentSubmission::query()->firstOrNew([
            'course_assignment_id' => $assignment->id,
            'student_id' => auth()->id(),
        ]);

        return view('student.assignments.show', compact('assignment', 'course', 'submission'));
    }

    public function submit(SubmitAssignmentRequest $request, CourseAssignment $assignment, AssignmentSubmissionService $service): RedirectResponse
    {
        $assignment->load('timelineItem.course');
        $course = $assignment->timelineItem->course;
        $this->authorize('view', $course);

        $submission = AssignmentSubmission::query()->firstOrNew([
            'course_assignment_id' => $assignment->id,
            'student_id' => $request->user()->id,
        ]);
        $this->authorize('submit', $submission);

        try {
            if ($assignment->submission_type === CourseAssignment::SUBMISSION_FILE) {
                $service->submitOrUpdate($request->user(), $assignment, null, $request->file('file'));
            } else {
                $service->submitOrUpdate($request->user(), $assignment, $request->validated('submission_text'), null);
            }
        } catch (\RuntimeException $e) {
            return back()->with('warning', $e->getMessage())->withInput();
        }

        return back()->with('status', __('Submission saved.'));
    }
}
