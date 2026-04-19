<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CancelTimelineItemRequest;
use App\Http\Requests\Admin\ReorderTimelineRequest;
use App\Http\Requests\Admin\TimelineRecordingRequest;
use App\Http\Requests\Admin\UpdateTimelineMetaRequest;
use App\Http\Requests\Admin\UpdateTimelineTimeRequest;
use App\Models\CourseSession;
use App\Models\TimelineItem;
use App\Models\ZoomMeeting;
use App\Services\TimelineService;
use App\Services\ZoomService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TimelineItemController extends Controller
{
    public function updateMeta(UpdateTimelineMetaRequest $request, TimelineItem $timelineItem, TimelineService $timeline): RedirectResponse
    {
        $timeline->updateSessionMeta($timelineItem, $request->validated('title'), $request->validated('description'));

        return back()->with('status', __('Session details updated.'));
    }

    public function updateTime(UpdateTimelineTimeRequest $request, TimelineItem $timelineItem, TimelineService $timeline): RedirectResponse
    {
        try {
            $timeline->updateSessionTime(
                $timelineItem,
                $request->validated('scheduled_start_time'),
                $request->validated('scheduled_end_time')
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('warning', $e->getMessage());
        }

        return back()->with('status', __('Session time updated.'));
    }

    public function cancel(CancelTimelineItemRequest $request, TimelineItem $timelineItem, TimelineService $timeline): RedirectResponse
    {
        $timeline->cancel($timelineItem, $request->validated('cancellation_reason'));

        return back()->with('status', __('Session cancelled.'));
    }

    public function recording(TimelineRecordingRequest $request, TimelineItem $timelineItem, TimelineService $timeline): RedirectResponse
    {
        $timeline->setRecording($timelineItem, $request->validated('recording_url'));

        return back()->with('status', __('Recording saved.'));
    }

    public function reorder(ReorderTimelineRequest $request, TimelineService $timeline): RedirectResponse
    {
        $timeline->reorder(
            (int) $request->validated('course_id'),
            $request->validated('scheduled_date'),
            array_map('intval', $request->validated('order'))
        );

        return back()->with('status', __('Order saved.'));
    }

    public function zoomStore(Request $request, TimelineItem $timelineItem, ZoomService $zoom): RedirectResponse
    {
        $this->authorize('manageZoom', $timelineItem);

        if (! $timelineItem->isSession()) {
            return back()->with('warning', __('Only sessions support Zoom links.'));
        }

        if ($timelineItem->scheduled_date->lt(Carbon::today())) {
            return back()->with('warning', __('Cannot create a Zoom link for a past date.'));
        }

        /** @var CourseSession $session */
        $session = $timelineItem->cardable;
        if (! $session instanceof CourseSession) {
            return back()->with('warning', __('Invalid session.'));
        }

        if ($session->zoomMeeting) {
            return back()->with('warning', __('A Zoom link already exists for this session.'));
        }

        $start = $timelineItem->scheduled_start_time;
        $end = $timelineItem->scheduled_end_time;
        if (! $start || ! $end) {
            return back()->with('warning', __('Set start and end time before creating a Zoom link.'));
        }

        try {
            $lecturerEmail = $timelineItem->course->lecturer?->email;
            $created = $zoom->createMeeting(
                $session,
                $timelineItem->scheduled_date->format('Y-m-d'),
                substr((string) $start, 0, 5),
                substr((string) $end, 0, 5),
                $lecturerEmail
            );

            ZoomMeeting::query()->create([
                'course_session_id' => $session->id,
                'zoom_meeting_id' => $created['zoom_meeting_id'],
                'topic' => $session->title,
                'start_at' => Carbon::parse($created['start_time']),
                'duration_minutes' => $created['duration'],
                'host_start_url' => $created['host_start_url'],
                'shared_join_url' => $created['join_url'],
                'password' => $created['password'],
                'settings' => [
                    'alternative_hosts' => $lecturerEmail,
                ],
                'created_by' => $request->user()?->id,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('warning', __('Could not create Zoom meeting. Check API credentials.'));
        }

        return back()->with('status', __('Zoom meeting created.'));
    }

    public function zoomDestroy(TimelineItem $timelineItem, ZoomService $zoom): RedirectResponse
    {
        $this->authorize('manageZoom', $timelineItem);

        if ($timelineItem->scheduled_date->lt(Carbon::today())) {
            return back()->with('warning', __('Cannot delete Zoom link for a past date.'));
        }

        $session = $timelineItem->cardable;
        if (! $session instanceof CourseSession) {
            return back()->with('warning', __('Invalid session.'));
        }

        $zm = $session->zoomMeeting;
        if (! $zm) {
            return back()->with('warning', __('No Zoom link to delete.'));
        }

        try {
            $zoom->deleteMeeting($zm->zoom_meeting_id);
        } catch (\Throwable) {
            // continue local delete
        }
        $zm->delete();

        return back()->with('status', __('Zoom link removed.'));
    }
}
