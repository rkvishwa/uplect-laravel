<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseSession;
use App\Models\TimelineItem;
use Illuminate\Http\RedirectResponse;

class ZoomHostRedirectController extends Controller
{
    public function __invoke(TimelineItem $timelineItem): RedirectResponse
    {
        $this->authorize('manageZoom', $timelineItem);

        if (! $timelineItem->isSession()) {
            abort(404);
        }

        $session = $timelineItem->cardable;
        if (! $session instanceof CourseSession) {
            abort(404);
        }

        $meeting = $session->zoomMeeting;
        if (! $meeting || $meeting->host_start_url === '') {
            abort(404);
        }

        return redirect()->away($meeting->host_start_url);
    }
}
