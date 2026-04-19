<?php

namespace App\Services;

use App\Models\Course;
use App\Models\IssuedCertificate;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateGeneratorService
{
    public function generate(User $student, Course $course): IssuedCertificate
    {
        $number = sprintf(
            'UPL-%d-%s-%04d',
            $course->id,
            now()->format('Y'),
            random_int(1, 9999)
        );

        while (IssuedCertificate::query()->where('certificate_number', $number)->exists()) {
            $number = sprintf(
                'UPL-%d-%s-%04d',
                $course->id,
                now()->format('Y'),
                random_int(1, 9999)
            );
        }

        $pdf = Pdf::loadView('pdf.certificate', [
            'student' => $student,
            'course' => $course,
            'certificateNumber' => $number,
            'issuedAt' => now(),
        ]);

        $relativePath = 'certificates/'.$course->id.'/'.Str::slug($number).'.pdf';
        Storage::disk('public')->put($relativePath, $pdf->output());

        return IssuedCertificate::query()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'certificate_number' => $number,
            'issued_at' => now(),
            'pdf_path' => $relativePath,
        ]);
    }
}
