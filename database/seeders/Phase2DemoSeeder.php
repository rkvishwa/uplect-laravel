<?php

namespace Database\Seeders;

use App\Models\AssignmentSubmission;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\CourseSession;
use App\Models\Enrollment;
use App\Models\TimelineItem;
use App\Models\User;
use App\Services\AssignmentSubmissionService;
use App\Services\TimelineService;
use Illuminate\Database\Seeder;

/**
 * Demo lecturers, courses with timelines, students, enrollments, and one graded submission.
 * Run: php artisan db:seed --class=Phase2DemoSeeder
 * Or set SEED_DEMO_DATA=true in .env and run db:seed.
 */
class Phase2DemoSeeder extends Seeder
{
    public function run(): void
    {
        $timeline = app(TimelineService::class);

        $lecturers = collect([1, 2, 3])->map(function (int $i) {
            return User::query()->updateOrCreate(
                ['email' => "lecturer{$i}@demo.uplect.test"],
                [
                    'name' => "Demo Lecturer {$i}",
                    'phone' => '077'.str_pad((string) (1000000 + $i), 7, '0', STR_PAD_LEFT),
                    'password' => 'password',
                    'role' => User::ROLE_LECTURER,
                    'email_verified_at' => now(),
                ]
            );
        });

        $categories = Category::query()->orderBy('id')->get();
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::query()->orderBy('id')->get();
        }

        $courses = collect();
        foreach ($categories->values() as $idx => $category) {
            /** @var User $lecturer */
            $lecturer = $lecturers[$idx % $lecturers->count()];
            $course = Course::query()->updateOrCreate(
                ['slug' => 'demo-'.$category->slug],
                [
                    'category_id' => $category->id,
                    'lecturer_id' => $lecturer->id,
                    'title' => 'Demo: '.$category->name,
                    'description' => 'Seeded course for local testing.',
                    'day_of_week' => 'mon',
                    'start_time' => '09:00:00',
                    'end_time' => '11:00:00',
                    'lecturer_payment_per_session_lkr' => 2500,
                    'student_total_fee_lkr' => 15000,
                    'status' => Course::STATUS_ACTIVE,
                ]
            );
            $courses->push($course);

            $this->clearCourseTimeline($course);

            $base = now()->addDays(7)->startOfDay();
            for ($d = 0; $d < 3; $d++) {
                $timeline->addSession($course, [
                    'scheduled_date' => $base->copy()->addDays($d)->toDateString(),
                    'scheduled_start_time' => '09:00',
                    'scheduled_end_time' => '10:30',
                    'title' => 'Session '.($d + 1),
                    'description' => 'Topics for session '.($d + 1).'.',
                    'resource_link' => null,
                    'is_makeup' => false,
                ]);
            }

            $timeline->addAssignment($course, [
                'scheduled_date' => $base->copy()->addDays(4)->toDateString(),
                'scheduled_start_time' => null,
                'scheduled_end_time' => null,
                'title' => 'Written assignment',
                'description' => 'Submit a short reflection.',
                'submission_type' => CourseAssignment::SUBMISSION_TEXT,
                'pass_mark' => 50,
                'max_mark' => 100,
                'is_required_for_certification' => true,
                'due_at' => $base->copy()->addDays(10),
                'instructions' => 'Write at least 200 words.',
                'max_file_size_mb' => 10,
            ]);

            $timeline->addCertificate($course, [
                'scheduled_date' => $base->copy()->addDays(45)->toDateString(),
                'scheduled_start_time' => null,
                'scheduled_end_time' => null,
                'title' => 'Certificate milestone',
                'description' => 'E-certificate when required work is complete.',
            ]);
        }

        $students = collect([1, 2, 3, 4, 5])->map(function (int $i) {
            return User::query()->updateOrCreate(
                ['email' => "student{$i}@demo.uplect.test"],
                [
                    'name' => "Demo Student {$i}",
                    'phone' => '078'.str_pad((string) (2000000 + $i), 7, '0', STR_PAD_LEFT),
                    'password' => 'password',
                    'role' => User::ROLE_STUDENT,
                    'email_verified_at' => now(),
                ]
            );
        });

        $primaryCourse = $courses->first();
        if ($primaryCourse === null) {
            return;
        }

        Enrollment::query()->whereIn('student_id', $students->pluck('id'))
            ->where('course_id', $primaryCourse->id)
            ->delete();

        Enrollment::query()->create([
            'course_id' => $primaryCourse->id,
            'student_id' => $students[0]->id,
            'payment_method' => Enrollment::PAYMENT_BANK_TRANSFER,
            'payment_status' => Enrollment::PAYMENT_PENDING,
            'amount_lkr' => $primaryCourse->student_total_fee_lkr,
            'submitted_at' => now(),
            'status' => Enrollment::STATUS_PENDING,
        ]);

        Enrollment::query()->create([
            'course_id' => $primaryCourse->id,
            'student_id' => $students[1]->id,
            'payment_method' => Enrollment::PAYMENT_BANK_TRANSFER,
            'payment_status' => Enrollment::PAYMENT_PAID,
            'amount_lkr' => $primaryCourse->student_total_fee_lkr,
            'submitted_at' => now(),
            'status' => Enrollment::STATUS_ACTIVE,
            'enrolled_at' => now(),
            'reviewed_at' => now(),
        ]);

        Enrollment::query()->create([
            'course_id' => $primaryCourse->id,
            'student_id' => $students[2]->id,
            'payment_method' => Enrollment::PAYMENT_BANK_TRANSFER,
            'payment_status' => Enrollment::PAYMENT_DECLINED,
            'amount_lkr' => $primaryCourse->student_total_fee_lkr,
            'submitted_at' => now(),
            'status' => Enrollment::STATUS_DECLINED,
            'reviewed_at' => now(),
        ]);

        Enrollment::query()->create([
            'course_id' => $primaryCourse->id,
            'student_id' => $students[3]->id,
            'payment_method' => Enrollment::PAYMENT_PAYHERE,
            'payment_status' => Enrollment::PAYMENT_PAID,
            'payment_reference' => 'demo-payhere',
            'amount_lkr' => $primaryCourse->student_total_fee_lkr,
            'submitted_at' => now(),
            'status' => Enrollment::STATUS_ACTIVE,
            'enrolled_at' => now(),
        ]);

        $assignmentItem = TimelineItem::query()
            ->where('course_id', $primaryCourse->id)
            ->where('cardable_type', CourseAssignment::class)
            ->first();

        if ($assignmentItem && $assignmentItem->cardable instanceof CourseAssignment) {
            $assignment = $assignmentItem->cardable;
            $submission = AssignmentSubmission::query()->updateOrCreate(
                [
                    'course_assignment_id' => $assignment->id,
                    'student_id' => $students[1]->id,
                ],
                [
                    'submission_text' => 'Demo submission text for grading.',
                    'submitted_at' => now(),
                    'status' => AssignmentSubmission::STATUS_SUBMITTED,
                ]
            );

            app(AssignmentSubmissionService::class)->grade(
                $submission,
                $primaryCourse->lecturer,
                75,
                'Good work (seeded).'
            );
        }

        $this->command->info('Phase2 demo data seeded. Log in as lecturer1@demo.uplect.test / password or student2@demo.uplect.test / password');
    }

    protected function clearCourseTimeline(Course $course): void
    {
        TimelineItem::query()->withTrashed()->where('course_id', $course->id)->get()->each(function (TimelineItem $item) {
            $cardable = $item->cardable;
            if ($cardable instanceof CourseSession) {
                $cardable->zoomMeeting?->forceDelete();
            }
            $cardable?->delete();
            $item->forceDelete();
        });
    }
}
