<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\CourseSession;
use App\Models\TimelineItem;
use Illuminate\Http\RedirectResponse;

class ZoomStartController extends Controller
{
    public function __invoke(TimelineItem $timelineItem): RedirectResponse
    {
        $this->authorize('startZoom', $timelineItem);

        if (! $timelineItem->isSession()) {
            abort(404);
        }

        $session = $timelineItem->cardable;
        if (! $session instanceof CourseSession) {
            abort(404);
        }

        $meeting = $session->zoomMeeting;
        $url = $meeting?->shared_join_url ?? $meeting?->host_start_url;
        if (! $url) {
            abort(404);
        }

        return redirect()->away($url);
    }
}
