<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseTimelineController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\TimelineItemController;
use App\Http\Controllers\Admin\ZoomHostRedirectController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Http\Controllers\Lecturer\LecturerAssignmentController;
use App\Http\Controllers\Lecturer\LecturerCourseController;
use App\Http\Controllers\Lecturer\LecturerTimelineController;
use App\Http\Controllers\Lecturer\ZoomStartController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\Payment\PayHereController;
use App\Http\Controllers\Student\CatalogController;
use App\Http\Controllers\Student\CourseDetailController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\StudentAssignmentController;
use App\Http\Controllers\Student\StudentCertificateController;
use App\Http\Controllers\Student\StudentEnrollmentController;
use App\Http\Controllers\Student\TimelineController as StudentTimelineController;
use App\Http\Controllers\Student\ZoomJoinController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/email/verify', function (Request $request) {
    $user = $request->user();

    if ($user === null) {
        return redirect()->route('login');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect()->to(match ($user->role) {
            User::ROLE_ADMIN => route('admin.dashboard'),
            User::ROLE_LECTURER => route('lecturer.dashboard'),
            default => route('student.dashboard'),
        });
    }

    return redirect()->route('otp.show', ['email' => $user->email]);
})->name('verification.notice');

Route::get('/terms-and-conditions', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/privacy-policy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/return-policy', [LegalController::class, 'returns'])->name('legal.returns');

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('otp.show');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/verify-otp/resend', [OtpVerificationController::class, 'resend'])
        ->middleware('throttle:otp-resend')
        ->name('otp.resend');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'update'])->name('password.update');
});

Route::post('/logout', [LogoutController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::post('/payment/payhere/notify', [PayHereController::class, 'notify'])->name('payhere.notify');
Route::middleware('auth')->get('/payment/payhere/return', [PayHereController::class, 'return'])->name('payhere.return');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['role:'.User::ROLE_ADMIN, 'admin.data'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::patch('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
        Route::delete('profile/avatar', [AdminProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::post('categories/{category}/activate', [CategoryController::class, 'activate'])->name('categories.activate');
        Route::post('categories/{category}/deactivate', [CategoryController::class, 'deactivate'])->name('categories.deactivate');

        Route::resource('courses', CourseController::class)->except(['show']);
        Route::post('courses/{course}/activate', [CourseController::class, 'activate'])->name('courses.activate');
        Route::post('courses/{course}/inactivate', [CourseController::class, 'inactivate'])->name('courses.inactivate');

        Route::get('courses/{course}/timeline', [CourseTimelineController::class, 'index'])->name('courses.timeline.index');
        Route::post('courses/{course}/timeline', [CourseTimelineController::class, 'store'])->name('courses.timeline.store');

        Route::patch('timeline-items/{timelineItem}/meta', [TimelineItemController::class, 'updateMeta'])->name('timeline-items.meta');
        Route::patch('timeline-items/{timelineItem}/time', [TimelineItemController::class, 'updateTime'])->name('timeline-items.time');
        Route::post('timeline-items/{timelineItem}/cancel', [TimelineItemController::class, 'cancel'])->name('timeline-items.cancel');
        Route::post('timeline-items/{timelineItem}/recording', [TimelineItemController::class, 'recording'])->name('timeline-items.recording');
        Route::post('timeline-items/reorder', [TimelineItemController::class, 'reorder'])->name('timeline-items.reorder');
        Route::post('timeline-items/{timelineItem}/zoom', [TimelineItemController::class, 'zoomStore'])->name('timeline-items.zoom.store');
        Route::delete('timeline-items/{timelineItem}/zoom', [TimelineItemController::class, 'zoomDestroy'])->name('timeline-items.zoom.destroy');
        Route::get('timeline-items/{timelineItem}/zoom/host', ZoomHostRedirectController::class)->name('timeline-items.zoom.host');

        Route::get('enrollments', [AdminEnrollmentController::class, 'index'])->name('enrollments.index');
        Route::post('enrollments/{enrollment}/approve', [AdminEnrollmentController::class, 'approve'])->name('enrollments.approve');
        Route::post('enrollments/{enrollment}/decline', [AdminEnrollmentController::class, 'decline'])->name('enrollments.decline');
        Route::get('enrollments/{enrollment}/slip', [AdminEnrollmentController::class, 'downloadSlip'])->name('enrollments.slip');

        Route::get('/lecturers', [LecturerController::class, 'index'])->name('lecturers.index');
        Route::post('/lecturers', [LecturerController::class, 'store'])->name('lecturers.store');
        Route::delete('/lecturers/{user}', [LecturerController::class, 'destroy'])->name('lecturers.destroy');
    });

    Route::middleware('role:'.User::ROLE_LECTURER)->prefix('lecturer')->name('lecturer.')->group(function () {
        Route::get('/', [LecturerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/timeline', [LecturerTimelineController::class, 'index'])->name('timeline.index');
        Route::get('/courses', [LecturerCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}/timeline', [LecturerCourseController::class, 'timeline'])->name('courses.timeline');
        Route::get('/assignments/{assignment}/edit', [LecturerAssignmentController::class, 'edit'])->name('assignments.edit');
        Route::patch('/assignments/{assignment}', [LecturerAssignmentController::class, 'update'])->name('assignments.update');
        Route::get('/assignments/{assignment}/submissions', [LecturerAssignmentController::class, 'submissions'])->name('assignments.submissions');
        Route::post('/submissions/{submission}/grade', [LecturerAssignmentController::class, 'grade'])->name('submissions.grade');
        Route::post('/submissions/{submission}/return', [LecturerAssignmentController::class, 'returnSubmission'])->name('submissions.return');
        Route::get('/submissions/{submission}/download', [LecturerAssignmentController::class, 'downloadSubmission'])->name('submissions.download');
        Route::get('/timeline-items/{timelineItem}/zoom/start', ZoomStartController::class)->name('timeline.zoom.start');
    });

    Route::middleware('role:'.User::ROLE_STUDENT)->prefix('student')->name('student.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/courses/{course}', CourseDetailController::class)->name('courses.show');
        Route::get('/enrollments', [StudentEnrollmentController::class, 'index'])->name('enrollments.index');
        Route::get('/enroll/{course}', [StudentEnrollmentController::class, 'create'])->name('enroll.create');
        Route::post('/enroll/bank', [StudentEnrollmentController::class, 'storeBank'])->name('enroll.bank');
        Route::post('/enroll/payhere', [StudentEnrollmentController::class, 'storePayHere'])->name('enroll.payhere');
        Route::get('/enrollments/{enrollment}/pay', [PayHereController::class, 'checkoutForm'])->name('payhere.checkout');
        Route::get('/timeline', [StudentTimelineController::class, 'combined'])->name('timeline.index');
        Route::get('/courses/{course}/timeline', [StudentTimelineController::class, 'course'])->name('courses.timeline');
        Route::get('/assignments/{assignment}', [StudentAssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/assignments/{assignment}', [StudentAssignmentController::class, 'submit'])->name('assignments.submit');
        Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/{certificate}/download', [StudentCertificateController::class, 'download'])->name('certificates.download');
        Route::get('/timeline-items/{timelineItem}/zoom/join', ZoomJoinController::class)
            ->middleware('throttle:zoom-join')
            ->name('timeline.zoom.join');
    });
});
