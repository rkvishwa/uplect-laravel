<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\TimelineItem;
use Illuminate\View\View;

class LecturerCourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->where('lecturer_id', auth()->id())
            ->with('category')
            ->orderBy('title')
            ->paginate(12);

        return view('lecturer.courses.index', compact('courses'));
    }

    public function timeline(Course $course): View
    {
        if ($course->lecturer_id !== auth()->id()) {
            abort(404);
        }

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

        return view('lecturer.courses.timeline', compact('course', 'grouped'));
    }
}
