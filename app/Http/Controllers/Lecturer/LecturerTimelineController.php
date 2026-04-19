<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\TimelineService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LecturerTimelineController extends Controller
{
    public function index(Request $request, TimelineService $timeline): View
    {
        $user = $request->user();
        $from = $request->input('from');
        $to = $request->input('to');
        $courseIds = $request->input('courses', []);
        if (! is_array($courseIds)) {
            $courseIds = [];
        }

        $items = $timeline->combinedFor($user, $from, $to, $courseIds);

        $courses = Course::query()
            ->where('lecturer_id', $user->id)
            ->orderBy('title')
            ->get();

        return view('lecturer.timeline.index', compact('items', 'courses', 'from', 'to', 'courseIds'));
    }
}
