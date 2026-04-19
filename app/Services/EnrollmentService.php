<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function enrollWithBankTransfer(
        User $student,
        Course $course,
        UploadedFile $slip,
        float $amountLkr
    ): Enrollment {
        return DB::transaction(function () use ($student, $course, $slip, $amountLkr) {
            $this->assertNoOpenEnrollment($student, $course);

            $path = $slip->store("enrollments/slips/{$course->id}", 'local');

            return Enrollment::query()->create([
                'course_id' => $course->id,
                'student_id' => $student->id,
                'payment_method' => Enrollment::PAYMENT_BANK_TRANSFER,
                'payment_status' => Enrollment::PAYMENT_PENDING,
                'bank_slip_path' => $path,
                'amount_lkr' => $amountLkr,
                'submitted_at' => now(),
                'status' => Enrollment::STATUS_PENDING,
            ]);
        });
    }

    public function enrollWithPayHere(User $student, Course $course, float $amountLkr): Enrollment
    {
        return DB::transaction(function () use ($student, $course, $amountLkr) {
            $this->assertNoOpenEnrollment($student, $course);

            $enrollment = Enrollment::query()->create([
                'course_id' => $course->id,
                'student_id' => $student->id,
                'payment_method' => Enrollment::PAYMENT_PAYHERE,
                'payment_status' => Enrollment::PAYMENT_PENDING,
                'payment_reference' => null,
                'amount_lkr' => $amountLkr,
                'submitted_at' => now(),
                'status' => Enrollment::STATUS_PENDING,
            ]);

            $enrollment->update([
                'payment_reference' => (string) $enrollment->id,
            ]);

            return $enrollment->fresh();
        });
    }

    public function approve(Enrollment $enrollment, User $admin): void
    {
        $enrollment->update([
            'status' => Enrollment::STATUS_ACTIVE,
            'payment_status' => Enrollment::PAYMENT_PAID,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'enrolled_at' => now(),
            'decline_reason' => null,
        ]);
    }

    public function decline(Enrollment $enrollment, User $admin): void
    {
        $enrollment->update([
            'status' => Enrollment::STATUS_DECLINED,
            'payment_status' => Enrollment::PAYMENT_DECLINED,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'decline_reason' => null,
        ]);
    }

    public function markPayHerePaid(Enrollment $enrollment, string $paymentId): void
    {
        $enrollment->update([
            'status' => Enrollment::STATUS_ACTIVE,
            'payment_status' => Enrollment::PAYMENT_PAID,
            'payment_reference' => $paymentId,
            'enrolled_at' => $enrollment->enrolled_at ?? now(),
        ]);
    }

    protected function assertNoOpenEnrollment(User $student, Course $course): void
    {
        $exists = Enrollment::query()
            ->where('course_id', $course->id)
            ->where('student_id', $student->id)
            ->whereIn('status', [
                Enrollment::STATUS_PENDING,
                Enrollment::STATUS_ACTIVE,
            ])
            ->exists();

        if ($exists) {
            throw new \RuntimeException(__('You already have a pending or active enrollment for this course.'));
        }
    }
}
