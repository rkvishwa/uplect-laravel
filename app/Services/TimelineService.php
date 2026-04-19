<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\CourseCertificate;
use App\Models\CourseSession;
use App\Models\Enrollment;
use App\Models\TimelineItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TimelineService
{
    public function __construct(
        protected ZoomService $zoomService
    ) {}

    public function addSession(
        Course $course,
        array $data,
        ?User $creator = null
    ): TimelineItem {
        return DB::transaction(function () use ($course, $data, $creator) {
            $session = CourseSession::query()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'resource_link' => $data['resource_link'] ?? null,
            ]);

            $order = $this->nextOrderIndex($course->id, $data['scheduled_date']);

            return TimelineItem::query()->create([
                'course_id' => $course->id,
                'scheduled_date' => $data['scheduled_date'],
                'scheduled_start_time' => $data['scheduled_start_time'] ?? null,
                'scheduled_end_time' => $data['scheduled_end_time'] ?? null,
                'order_index' => $order,
                'cardable_type' => CourseSession::class,
                'cardable_id' => $session->id,
                'status' => TimelineItem::STATUS_SCHEDULED,
                'is_makeup' => (bool) ($data['is_makeup'] ?? false),
                'created_by' => $creator?->id,
            ]);
        });
    }

    public function addAssignment(Course $course, array $data, ?User $creator = null): TimelineItem
    {
        return DB::transaction(function () use ($course, $data, $creator) {
            $assignment = CourseAssignment::query()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'submission_type' => $data['submission_type'] ?? CourseAssignment::SUBMISSION_TEXT,
                'allowed_mime_types' => $data['allowed_mime_types'] ?? null,
                'max_file_size_mb' => $data['max_file_size_mb'] ?? 10,
                'pass_mark' => $data['pass_mark'] ?? 50,
                'max_mark' => $data['max_mark'] ?? 100,
                'is_required_for_certification' => (bool) ($data['is_required_for_certification'] ?? false),
                'due_at' => $data['due_at'] ?? null,
                'instructions' => $data['instructions'] ?? null,
            ]);

            $order = $this->nextOrderIndex($course->id, $data['scheduled_date']);

            return TimelineItem::query()->create([
                'course_id' => $course->id,
                'scheduled_date' => $data['scheduled_date'],
                'scheduled_start_time' => $data['scheduled_start_time'] ?? null,
                'scheduled_end_time' => $data['scheduled_end_time'] ?? null,
                'order_index' => $order,
                'cardable_type' => CourseAssignment::class,
                'cardable_id' => $assignment->id,
                'status' => TimelineItem::STATUS_SCHEDULED,
                'created_by' => $creator?->id,
            ]);
        });
    }

    public function addCertificate(Course $course, array $data, ?User $creator = null): TimelineItem
    {
        return DB::transaction(function () use ($course, $data, $creator) {
            $cert = CourseCertificate::query()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
            ]);

            $order = $this->nextOrderIndex($course->id, $data['scheduled_date']);

            return TimelineItem::query()->create([
                'course_id' => $course->id,
                'scheduled_date' => $data['scheduled_date'],
                'scheduled_start_time' => $data['scheduled_start_time'] ?? null,
                'scheduled_end_time' => $data['scheduled_end_time'] ?? null,
                'order_index' => $order,
                'cardable_type' => CourseCertificate::class,
                'cardable_id' => $cert->id,
                'status' => TimelineItem::STATUS_SCHEDULED,
                'created_by' => $creator?->id,
            ]);
        });
    }

    public function updateSessionMeta(TimelineItem $item, string $title, ?string $description): void
    {
        $session = $item->cardable;
        if (! $session instanceof CourseSession) {
            return;
        }

        $session->update([
            'title' => $title,
            'description' => $description,
        ]);

        $zm = $session->zoomMeeting;
        if ($zm && ($account = $zm->zoomAccount)) {
            try {
                $this->zoomService->forAccount($account)->updateMeeting($zm->zoom_meeting_id, [
                    'topic' => $title,
                ]);
                $zm->update(['topic' => $title]);
            } catch (\Throwable) {
                // non-fatal: Zoom sync optional if meeting missing
            }
        }
    }

    public function updateSessionTime(TimelineItem $item, string $startTime, string $endTime): void
    {
        $date = $item->scheduled_date->format('Y-m-d');
        $start = Carbon::parse($date.' '.$startTime);
        $end = Carbon::parse($date.' '.$endTime);
        if (! $start->isSameDay(Carbon::parse($date)) || ! $end->isSameDay(Carbon::parse($date))) {
            throw new \InvalidArgumentException(__('Times must be on the same day as the session.'));
        }
        if ($end->lessThanOrEqualTo($start)) {
            throw new \InvalidArgumentException(__('End time must be after start time.'));
        }

        $item->update([
            'scheduled_start_time' => $startTime,
            'scheduled_end_time' => $endTime,
        ]);

        $session = $item->cardable;
        $zm = $session instanceof CourseSession ? $session->zoomMeeting : null;
        if ($zm && ($account = $zm->zoomAccount)) {
            $timezone = (string) ($account->timezone ?: 'Asia/Colombo');
            $startAt = Carbon::parse($date.' '.$startTime, $timezone)->utc();
            $duration = max(1, $start->diffInMinutes($end));
            $this->zoomService->forAccount($account)->updateMeeting($zm->zoom_meeting_id, [
                'start_time' => $startAt->format('Y-m-d\TH:i:s\Z'),
                'duration' => $duration,
            ]);
            $zm->update([
                'start_at' => $startAt,
                'duration_minutes' => $duration,
            ]);
        }
    }

    public function cancel(TimelineItem $item, string $reason): void
    {
        $item->update([
            'status' => TimelineItem::STATUS_CANCELLED,
            'cancellation_reason' => $reason,
        ]);

        $cardable = $item->cardable;
        if ($cardable instanceof CourseSession) {
            $zm = $cardable->zoomMeeting;
            if ($zm) {
                $account = $zm->zoomAccount;
                if ($account) {
                    try {
                        $this->zoomService->forAccount($account)->deleteMeeting($zm->zoom_meeting_id);
                    } catch (\Throwable) {
                        // ignore
                    }
                }
                $zm->delete();
            }
        }
    }

    public function setRecording(TimelineItem $item, string $url): void
    {
        $session = $item->cardable;
        if (! $session instanceof CourseSession) {
            return;
        }
        $session->update([
            'recording_url' => $url,
            'recording_added_at' => now(),
        ]);
    }

    /**
     * @param  array<int, int>  $orderedIds  timeline_item ids in display order for one date group
     */
    public function reorder(int $courseId, string $scheduledDate, array $orderedIds): void
    {
        DB::transaction(function () use ($courseId, $scheduledDate, $orderedIds) {
            foreach ($orderedIds as $index => $id) {
                TimelineItem::query()
                    ->where('course_id', $courseId)
                    ->whereDate('scheduled_date', $scheduledDate)
                    ->whereKey($id)
                    ->update(['order_index' => $index]);
            }
        });
    }

    /**
     * @return Collection<int, TimelineItem>
     */
    public function combinedFor(User $user, ?string $from = null, ?string $to = null, ?array $courseIds = null): Collection
    {
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : now()->startOfDay();
        $toDate = $to ? Carbon::parse($to)->endOfDay() : now()->copy()->addDays(14)->endOfDay();

        $q = TimelineItem::query()
            ->with(['course.lecturer', 'cardable'])
            ->whereBetween('scheduled_date', [$fromDate->toDateString(), $toDate->toDateString()])
            ->orderBy('scheduled_date')
            ->orderBy('order_index');

        if ($user->isLecturer()) {
            $q->whereHas('course', fn (Builder $b) => $b->where('lecturer_id', $user->id));
        } elseif ($user->isStudent()) {
            $q->whereHas('course.enrollments', function (Builder $b) use ($user) {
                $b->where('student_id', $user->id)
                    ->where('status', Enrollment::STATUS_ACTIVE);
            });
        } else {
            return collect();
        }

        if (is_array($courseIds) && $courseIds !== []) {
            $q->whereIn('course_id', $courseIds);
        }

        return $q->get();
    }

    protected function nextOrderIndex(int $courseId, string $scheduledDate): int
    {
        $max = TimelineItem::query()
            ->where('course_id', $courseId)
            ->whereDate('scheduled_date', $scheduledDate)
            ->max('order_index');

        return (int) $max + 1;
    }
}
