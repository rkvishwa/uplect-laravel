<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeclineEnrollmentRequest;
use App\Models\Enrollment;
use App\Services\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Enrollment::class);

        $q = Enrollment::query()->with(['course', 'student'])->orderByDesc('created_at');

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        } else {
            $q->where('status', Enrollment::STATUS_PENDING);
        }

        $enrollments = $q->paginate(20)->withQueryString();

        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function approve(Enrollment $enrollment, EnrollmentService $service): RedirectResponse
    {
        $this->authorize('approve', $enrollment);

        if ($enrollment->status !== Enrollment::STATUS_PENDING) {
            return back()->with('warning', __('Only pending enrollments can be approved.'));
        }

        $service->approve($enrollment, request()->user());

        return back()->with('status', __('Enrollment approved.'));
    }

    public function decline(DeclineEnrollmentRequest $request, Enrollment $enrollment, EnrollmentService $service): RedirectResponse
    {
        $this->authorize('approve', $enrollment);

        if ($enrollment->status !== Enrollment::STATUS_PENDING) {
            return back()->with('warning', __('Only pending enrollments can be declined.'));
        }

        $service->decline($enrollment, $request->user(), $request->validated('decline_reason'));

        return back()->with('status', __('Enrollment declined.'));
    }

    public function downloadSlip(Enrollment $enrollment): StreamedResponse|RedirectResponse
    {
        $this->authorize('downloadSlip', $enrollment);

        if (! $enrollment->bank_slip_path || ! Storage::disk('local')->exists($enrollment->bank_slip_path)) {
            return back()->with('warning', __('File not found.'));
        }

        return Storage::disk('local')->download($enrollment->bank_slip_path);
    }
}
