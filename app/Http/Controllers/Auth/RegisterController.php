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
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:32'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'accept_policies' => ['required', 'accepted'],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => $validated['password'],
            'role' => User::ROLE_STUDENT,
            'email_verified_at' => null,
        ]);

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

        return redirect()
            ->route('otp.show', ['email' => $user->email])
            ->with('status', __('We sent a verification code to your email.'));
    }

    protected function generateOtpCode(): string
    {
        return str_pad((string) random_int(0, 999_999), 6, '0', STR_PAD_LEFT);
    }
}
