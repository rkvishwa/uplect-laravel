<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/email/verify', function (\Illuminate\Http\Request $request) {
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('role:'.User::ROLE_ADMIN)->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/lecturers', [LecturerController::class, 'index'])->name('lecturers.index');
        Route::post('/lecturers', [LecturerController::class, 'store'])->name('lecturers.store');
        Route::delete('/lecturers/{user}', [LecturerController::class, 'destroy'])->name('lecturers.destroy');
    });

    Route::middleware('role:'.User::ROLE_LECTURER)->prefix('lecturer')->name('lecturer.')->group(function () {
        Route::get('/', [LecturerDashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware('role:'.User::ROLE_STUDENT)->prefix('student')->name('student.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'index'])->name('dashboard');
    });
});
