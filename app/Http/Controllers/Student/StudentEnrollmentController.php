<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreBankEnrollmentRequest;
use App\Http\Requests\Student\StorePayHereEnrollmentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Services\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentEnrollmentController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Enrollment::class);

        $enrollments = Enrollment::query()
            ->where('student_id', auth()->id())
            ->with('course')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('student.enrollments.index', compact('enrollments'));
    }

    public function create(Course $course): View
    {
        $this->authorize('viewCatalogDetail', $course);

        return view('student.enrollments.create', compact('course'));
    }

    public function storeBank(StoreBankEnrollmentRequest $request, EnrollmentService $service): RedirectResponse
    {
        $course = Course::query()->findOrFail($request->validated('course_id'));
        $this->authorize('viewCatalogDetail', $course);

        try {
            $service->enrollWithBankTransfer(
                $request->user(),
                $course,
                $request->file('slip'),
                (float) $course->student_total_fee_lkr
            );
        } catch (\RuntimeException $e) {
            return back()->with('warning', $e->getMessage())->withInput();
        }

        return redirect()->route('student.enrollments.index')->with('status', __('Enrollment submitted for review.'));
    }

    public function storePayHere(StorePayHereEnrollmentRequest $request, EnrollmentService $service): RedirectResponse
    {
        $course = Course::query()->findOrFail($request->validated('course_id'));
        $this->authorize('viewCatalogDetail', $course);

        try {
            $enrollment = $service->enrollWithPayHere(
                $request->user(),
                $course,
                (float) $course->student_total_fee_lkr
            );
        } catch (\RuntimeException $e) {
            return back()->with('warning', $e->getMessage())->withInput();
        }

        return redirect()->route('student.payhere.checkout', $enrollment);
    }
}
