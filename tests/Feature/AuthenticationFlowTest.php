<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_log_in_and_reach_dashboard(): void
    {
        $this->seed(AdminSeeder::class);

        $response = $this->post('/login', [
            'email' => 'admin@uplect.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_student_registration_redirects_to_otp_and_sends_mail(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'phone' => '1234567890',
            'password' => 'password12',
            'password_confirmation' => 'password12',
        ]);

        $response->assertRedirect(route('otp.show', ['email' => 'student@test.com']));
        Mail::assertSent(OtpMail::class);
    }

    public function test_unverified_login_redirects_to_otp_flow(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'Test Student',
            'email' => 'unverified@test.com',
            'phone' => '1234567890',
            'password' => 'password12',
            'password_confirmation' => 'password12',
        ]);

        $response = $this->post('/login', [
            'email' => 'unverified@test.com',
            'password' => 'password12',
        ]);

        $response->assertRedirect(route('otp.show', ['email' => 'unverified@test.com']));
        $this->assertGuest();
    }
}
