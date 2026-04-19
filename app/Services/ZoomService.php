<?php

namespace App\Services;

use App\Models\CourseSession;
use App\Models\User;
use App\Models\ZoomAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    protected ?ZoomAccount $account = null;

    public function forAccount(ZoomAccount $account): self
    {
        $instance = clone $this;
        $instance->account = $account;

        return $instance;
    }

    protected function tokenCacheKey(): string
    {
        if ($this->account === null) {
            throw new \RuntimeException('No Zoom account selected.');
        }

        return 'zoom.access_token.'.$this->account->id;
    }

    public function getAccessToken(): string
    {
        if ($this->account === null) {
            throw new \RuntimeException('No Zoom account selected.');
        }

        $cached = Cache::get($this->tokenCacheKey());
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $accountId = $this->account->account_id;
        $clientId = $this->account->client_id;
        $clientSecret = $this->account->client_secret;

        if ($accountId === '' || $clientId === '' || $clientSecret === '') {
            throw new \RuntimeException('Zoom API credentials are not configured for this account.');
        }

        $response = Http::asForm()
            ->withBasicAuth((string) $clientId, (string) $clientSecret)
            ->post('https://zoom.us/oauth/token', [
                'grant_type' => 'account_credentials',
                'account_id' => $accountId,
            ]);

        if (! $response->successful()) {
            Log::warning('Zoom OAuth failed', ['body' => $response->body(), 'zoom_account_id' => $this->account->id]);
            throw new \RuntimeException('Could not authenticate with Zoom.');
        }

        $data = $response->json();
        $token = $data['access_token'] ?? null;
        $expiresIn = (int) ($data['expires_in'] ?? 3600);
        if (! is_string($token) || $token === '') {
            throw new \RuntimeException('Invalid Zoom token response.');
        }

        Cache::put($this->tokenCacheKey(), $token, now()->addSeconds(max(60, $expiresIn - 60)));

        return $token;
    }

    /**
     * @return array{zoom_meeting_id: string, host_start_url: string, join_url: string|null, password: string|null, start_time: string, duration: int}
     */
    public function createMeeting(CourseSession $session, string $scheduledDate, string $startTime, string $endTime, ?string $alternativeHostEmail): array
    {
        if ($this->account === null) {
            throw new \RuntimeException('No Zoom account selected.');
        }

        $hostUserId = $this->account->host_user_id;
        if ($hostUserId === '' || $hostUserId === null) {
            throw new \RuntimeException('Zoom host user is not set for this account.');
        }

        $timezone = (string) ($this->account->timezone ?: 'Asia/Colombo');
        $startLocal = Carbon::parse($scheduledDate.' '.$startTime, $timezone);
        $endLocal = Carbon::parse($scheduledDate.' '.$endTime, $timezone);
        $duration = max(1, $startLocal->diffInMinutes($endLocal));
        $startUtc = $startLocal->clone()->utc()->format('Y-m-d\TH:i:s\Z');

        $settings = [
            'approval_type' => 0,
            'registration_type' => 1,
            'waiting_room' => (bool) $this->account->waiting_room,
        ];
        if ($alternativeHostEmail) {
            $settings['alternative_hosts'] = $alternativeHostEmail;
        }

        $payload = [
            'topic' => $session->title,
            'type' => 2,
            'start_time' => $startUtc,
            'duration' => $duration,
            'timezone' => $timezone,
            'settings' => $settings,
        ];

        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->post('https://api.zoom.us/v2/users/'.urlencode((string) $hostUserId).'/meetings', $payload);

        if (! $response->successful()) {
            Log::warning('Zoom create meeting failed', ['body' => $response->body(), 'zoom_account_id' => $this->account->id]);
            throw new \RuntimeException('Zoom could not create the meeting.');
        }

        $data = $response->json();

        return [
            'zoom_meeting_id' => (string) ($data['id'] ?? ''),
            'host_start_url' => (string) ($data['start_url'] ?? ''),
            'join_url' => isset($data['join_url']) ? (string) $data['join_url'] : null,
            'password' => isset($data['password']) ? (string) $data['password'] : null,
            'start_time' => $startUtc,
            'duration' => $duration,
        ];
    }

    /**
     * @param  array<string, mixed>  $patch
     */
    public function updateMeeting(string $zoomMeetingId, array $patch): void
    {
        if ($this->account === null) {
            throw new \RuntimeException('No Zoom account selected.');
        }

        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->patch('https://api.zoom.us/v2/meetings/'.urlencode($zoomMeetingId), $patch);

        if (! $response->successful()) {
            Log::warning('Zoom update meeting failed', ['body' => $response->body(), 'zoom_account_id' => $this->account->id]);
            throw new \RuntimeException('Zoom could not update the meeting.');
        }
    }

    public function deleteMeeting(string $zoomMeetingId): void
    {
        if ($this->account === null) {
            throw new \RuntimeException('No Zoom account selected.');
        }

        $response = Http::withToken($this->getAccessToken())
            ->delete('https://api.zoom.us/v2/meetings/'.urlencode($zoomMeetingId));

        if ($response->status() === 404) {
            return;
        }

        if (! $response->successful()) {
            Log::warning('Zoom delete meeting failed', ['body' => $response->body(), 'zoom_account_id' => $this->account->id]);
            throw new \RuntimeException('Zoom could not delete the meeting.');
        }
    }

    /**
     * @return array{registrant_id: string, join_url: string}
     */
    public function addRegistrant(string $zoomMeetingId, User $student): array
    {
        if ($this->account === null) {
            throw new \RuntimeException('No Zoom account selected.');
        }

        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->post('https://api.zoom.us/v2/meetings/'.urlencode($zoomMeetingId).'/registrants', [
                'first_name' => explode(' ', $student->name, 2)[0] ?? $student->name,
                'last_name' => explode(' ', $student->name, 2)[1] ?? ' ',
                'email' => $student->email,
                'auto_approve' => true,
            ]);

        if (! $response->successful()) {
            Log::warning('Zoom add registrant failed', ['body' => $response->body(), 'zoom_account_id' => $this->account->id]);
            throw new \RuntimeException('Zoom could not register the attendee.');
        }

        $data = $response->json();

        return [
            'registrant_id' => (string) ($data['registrant_id'] ?? ''),
            'join_url' => (string) ($data['join_url'] ?? ''),
        ];
    }
}
