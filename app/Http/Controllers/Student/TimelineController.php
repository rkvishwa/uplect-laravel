<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\TimelineItem;
use App\Services\TimelineService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimelineController extends Controller
{
    public function combined(Request $request, TimelineService $timeline): View
    {
        $user = $request->user();
        $from = $request->input('from');
        $to = $request->input('to');
        $courseIds = $request->input('courses', []);
        if (! is_array($courseIds)) {
            $courseIds = [];
        }

        $items = $timeline->combinedFor($user, $from, $to, $courseIds);

        $courses = $user->enrollments()
            ->where('status', \App\Models\Enrollment::STATUS_ACTIVE)
            ->with('course')
            ->get()
            ->pluck('course')
            ->filter();

        return view('student.timeline.index', compact('items', 'courses', 'from', 'to', 'courseIds'));
    }

    public function course(Course $course, TimelineService $timeline): View
    {
        $this->authorize('view', $course);

        $grouped = TimelineItem::query()
            ->where('course_id', $course->id)
            ->with([
                'cardable' => function (\Illuminate\Database\Eloquent\Relations\MorphTo $morphTo) {
                    $morphTo->morphWith([
                        CourseSession::class => ['zoomMeeting'],
                    ]);
                },
            ])
            ->orderBy('scheduled_date')
            ->orderBy('order_index')
            ->get()
            ->groupBy(fn (TimelineItem $i) => $i->scheduled_date->format('Y-m-d'));

        return view('student.timeline.course', compact('course', 'grouped'));
    }
}
