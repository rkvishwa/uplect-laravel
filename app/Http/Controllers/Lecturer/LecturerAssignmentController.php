<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lecturer\GradeSubmissionRequest;
use App\Http\Requests\Lecturer\UpdateLecturerAssignmentRequest;
use App\Models\AssignmentSubmission;
use App\Models\CourseAssignment;
use App\Services\AssignmentSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LecturerAssignmentController extends Controller
{
    public function edit(CourseAssignment $assignment): View
    {
        $this->authorize('update', $assignment);
        $assignment->load('timelineItem.course');

        return view('lecturer.assignments.edit', compact('assignment'));
    }

    public function update(UpdateLecturerAssignmentRequest $request, CourseAssignment $assignment): RedirectResponse
    {
        $assignment->update($request->validated());

        return back()->with('status', __('Assignment updated.'));
    }

    public function submissions(CourseAssignment $assignment): View
    {
        $this->authorize('grade', $assignment);
        $assignment->load(['timelineItem.course', 'submissions.student']);

        return view('lecturer.assignments.submissions', compact('assignment'));
    }

    public function grade(GradeSubmissionRequest $request, AssignmentSubmission $submission, AssignmentSubmissionService $service): RedirectResponse
    {
        $this->authorize('grade', $submission);
        $service->grade(
            $submission,
            $request->user(),
            (float) $request->validated('marks'),
            $request->validated('feedback')
        );

        return back()->with('status', __('Graded.'));
    }

    public function returnSubmission(AssignmentSubmission $submission, AssignmentSubmissionService $service): RedirectResponse
    {
        $this->authorize('grade', $submission);
        $service->returnToStudent($submission, request()->user());

        return back()->with('status', __('Returned to student.'));
    }

    public function downloadSubmission(AssignmentSubmission $submission): StreamedResponse|RedirectResponse
    {
        $this->authorize('grade', $submission);

        if (! $submission->submission_file_path || ! Storage::disk('local')->exists($submission->submission_file_path)) {
            return back()->with('warning', __('File not found.'));
        }

        return Storage::disk('local')->download($submission->submission_file_path);
    }
}
