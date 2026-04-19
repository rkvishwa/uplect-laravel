<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTimelineCardRequest;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\TimelineItem;
use App\Services\TimelineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseTimelineController extends Controller
{
    public function index(Course $course): View
    {
        $this->authorize('manageTimeline', $course);

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

        return view('admin.courses.timeline', compact('course', 'grouped'));
    }

    public function store(StoreTimelineCardRequest $request, Course $course, TimelineService $timeline): RedirectResponse
    {
        $this->authorize('manageTimeline', $course);

        $data = $request->validated();
        $type = $data['card_type'];
        unset($data['card_type']);

        $payload = [
            'scheduled_date' => $data['scheduled_date'],
            'scheduled_start_time' => $data['scheduled_start_time'] ?? null,
            'scheduled_end_time' => $data['scheduled_end_time'] ?? null,
            'is_makeup' => $data['is_makeup'] ?? false,
        ];

        if ($type === 'session') {
            $payload['title'] = $data['title'];
            $payload['description'] = $data['description'] ?? null;
            $payload['resource_link'] = $data['resource_link'] ?? null;
            $timeline->addSession($course, $payload, $request->user());
        } elseif ($type === 'assignment') {
            $payload['title'] = $data['title'];
            $payload['description'] = $data['description'] ?? null;
            $payload['submission_type'] = $data['submission_type'];
            $payload['pass_mark'] = $data['pass_mark'];
            $payload['max_mark'] = $data['max_mark'];
            $payload['is_required_for_certification'] = $request->boolean('is_required_for_certification');
            $payload['due_at'] = ! empty($data['due_at']) ? $data['due_at'] : null;
            $payload['instructions'] = $data['instructions'] ?? null;
            $payload['max_file_size_mb'] = $data['max_file_size_mb'] ?? 10;
            $timeline->addAssignment($course, $payload, $request->user());
        } else {
            $payload['title'] = $data['title'];
            $payload['description'] = $data['description'] ?? null;
            $timeline->addCertificate($course, $payload, $request->user());
        }

        return back()->with('status', __('Timeline updated.'));
    }
}
