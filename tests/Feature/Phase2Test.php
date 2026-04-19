<?php

namespace Tests\Feature;

use App\Models\AssignmentSubmission;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\Enrollment;
use App\Models\TimelineItem;
use App\Models\User;
use App\Services\AssignmentSubmissionService;
use App\Services\TimelineService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase2Test extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_pending_bank_enrollment(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $lecturer = User::factory()->create(['role' => User::ROLE_LECTURER]);
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $category = Category::query()->create([
            'name' => 'Test',
            'slug' => 'test',
            'is_active' => true,
        ]);

        $course = Course::query()->create([
            'category_id' => $category->id,
            'lecturer_id' => $lecturer->id,
            'title' => 'Intro',
            'slug' => 'intro',
            'description' => 'Desc',
            'day_of_week' => 'mon',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'lecturer_payment_per_session_lkr' => 1000,
            'student_total_fee_lkr' => 5000,
            'status' => Course::STATUS_ACTIVE,
        ]);

        $enrollment = Enrollment::query()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'payment_method' => Enrollment::PAYMENT_BANK_TRANSFER,
            'payment_status' => Enrollment::PAYMENT_PENDING,
            'amount_lkr' => 5000,
            'submitted_at' => now(),
            'status' => Enrollment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.enrollments.approve', $enrollment));

        $response->assertSessionHasNoErrors();
        $enrollment->refresh();
        $this->assertSame(Enrollment::STATUS_ACTIVE, $enrollment->status);
    }

    public function test_course_cannot_be_deleted_with_active_enrollment(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $lecturer = User::factory()->create(['role' => User::ROLE_LECTURER]);
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $category = Category::query()->create([
            'name' => 'Test',
            'slug' => 'test',
            'is_active' => true,
        ]);

        $course = Course::query()->create([
            'category_id' => $category->id,
            'lecturer_id' => $lecturer->id,
            'title' => 'Intro',
            'slug' => 'intro',
            'description' => 'Desc',
            'day_of_week' => 'mon',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'lecturer_payment_per_session_lkr' => 1000,
            'student_total_fee_lkr' => 5000,
            'status' => Course::STATUS_ACTIVE,
        ]);

        Enrollment::query()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'payment_method' => Enrollment::PAYMENT_BANK_TRANSFER,
            'payment_status' => Enrollment::PAYMENT_PAID,
            'amount_lkr' => 5000,
            'submitted_at' => now(),
            'status' => Enrollment::STATUS_ACTIVE,
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.courses.destroy', $course));

        $response->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }

    public function test_lecturer_can_grade_assignment_submission(): void
    {
        $lecturer = User::factory()->create(['role' => User::ROLE_LECTURER]);
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $category = Category::query()->create([
            'name' => 'Test',
            'slug' => 'test-grade',
            'is_active' => true,
        ]);

        $course = Course::query()->create([
            'category_id' => $category->id,
            'lecturer_id' => $lecturer->id,
            'title' => 'Grading course',
            'slug' => 'grading-course',
            'description' => 'Desc',
            'day_of_week' => 'mon',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'lecturer_payment_per_session_lkr' => 1000,
            'student_total_fee_lkr' => 5000,
            'status' => Course::STATUS_ACTIVE,
        ]);

        $timeline = app(TimelineService::class);
        $timeline->addAssignment($course, [
            'scheduled_date' => now()->addDay()->toDateString(),
            'title' => 'Essay',
            'description' => 'Write something',
            'submission_type' => CourseAssignment::SUBMISSION_TEXT,
            'pass_mark' => 40,
            'max_mark' => 100,
            'is_required_for_certification' => false,
        ]);

        $assignment = TimelineItem::query()
            ->where('course_id', $course->id)
            ->where('cardable_type', CourseAssignment::class)
            ->first()
            ->cardable;

        $submission = AssignmentSubmission::query()->create([
            'course_assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'submission_text' => 'Answer',
            'submitted_at' => now(),
            'status' => AssignmentSubmission::STATUS_SUBMITTED,
        ]);

        app(AssignmentSubmissionService::class)->grade($submission, $lecturer, 88, 'Nice');

        $submission->refresh();
        $this->assertSame(AssignmentSubmission::STATUS_GRADED, $submission->status);
        $this->assertEquals(88.0, (float) $submission->marks);
    }
}
