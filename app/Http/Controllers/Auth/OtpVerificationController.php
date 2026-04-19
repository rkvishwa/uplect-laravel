<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $email = (string) $request->query('email', $request->old('email', session('email', '')));

        if ($email === '') {
            return redirect()->route('register')->with('warning', __('Please register first to receive a verification code.'));
        }

        $user = User::query()->where('email', $email)->first();

        if ($user === null) {
            return redirect()->route('register')->with('warning', __('We could not find an account for that email.'));
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', __('Your email is already verified. You can sign in.'));
        }

        return view('auth.verify-otp', [
            'email' => $email,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if ($user === null) {
            throw ValidationException::withMessages([
                'otp' => __('Invalid verification request.'),
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', __('Your email is already verified. You can sign in.'));
        }

        $record = EmailOtp::query()->where('email', $user->email)->first();

        if ($record === null) {
            throw ValidationException::withMessages([
                'otp' => __('No active verification code. Please request a new one.'),
            ]);
        }

        if ($record->expires_at->isPast()) {
            $record->delete();

            throw ValidationException::withMessages([
                'otp' => __('This code has expired. Please request a new one.'),
            ]);
        }

        if ($record->attempts >= 5) {
            $record->delete();

            throw ValidationException::withMessages([
                'otp' => __('Too many attempts. Please request a new code.'),
            ]);
        }

        if (! Hash::check($validated['otp'], $record->otp_hash)) {
            $record->increment('attempts');

            throw ValidationException::withMessages([
                'otp' => __('That code is incorrect. Please try again.'),
            ]);
        }

        $user->markEmailAsVerified();

        $record->delete();

        return redirect()->route('login')->with('status', __('Email verified. You can now sign in.'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if ($user === null) {
            return back()->with('warning', __('We could not find an account for that email.'));
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', __('Your email is already verified. You can sign in.'));
        }

        $plainOtp = $this->generateOtpCode();

        EmailOtp::query()->updateOrCreate(
            ['email' => $user->email],
            [
                'otp_hash' => Hash::make($plainOtp),
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
            ]
        );

        Mail::to($user->email)->send(new OtpMail($plainOtp, $user->name));

        return back()->with('status', __('We sent a new verification code to your email.'));
    }

    private function generateOtpCode(): string
    {
        return str_pad((string) random_int(0, 999_999), 6, '0', STR_PAD_LEFT);
    }
}
