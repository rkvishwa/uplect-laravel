<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseSession;
use App\Models\TimelineItem;
use App\Models\ZoomMeetingRegistrant;
use App\Services\ZoomService;
use Illuminate\Http\RedirectResponse;

class ZoomJoinController extends Controller
{
    public function __invoke(TimelineItem $timelineItem, ZoomService $zoom): RedirectResponse
    {
        $this->authorize('joinZoom', $timelineItem);

        if (! $timelineItem->isSession()) {
            abort(404);
        }

        $session = $timelineItem->cardable;
        if (! $session instanceof CourseSession) {
            abort(404);
        }

        $meeting = $session->zoomMeeting;
        if (! $meeting) {
            abort(404);
        }

        $student = request()->user();
        if ($student === null) {
            abort(403);
        }

        $existing = ZoomMeetingRegistrant::query()
            ->where('meeting_id', $meeting->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing) {
            return redirect()->away($existing->join_url);
        }

        $account = $meeting->zoomAccount;
        if (! $account) {
            abort(503, __('This meeting is missing Zoom account configuration.'));
        }

        try {
            $created = $zoom->forAccount($account)->addRegistrant($meeting->zoom_meeting_id, $student);
            $registrant = ZoomMeetingRegistrant::query()->create([
                'meeting_id' => $meeting->id,
                'student_id' => $student->id,
                'zoom_registrant_id' => $created['registrant_id'],
                'join_url' => $created['join_url'],
                'registered_at' => now(),
            ]);

            return redirect()->away($registrant->join_url);
        } catch (\Throwable $e) {
            report($e);
            abort(503, __('Could not join meeting right now.'));
        }
    }
}
