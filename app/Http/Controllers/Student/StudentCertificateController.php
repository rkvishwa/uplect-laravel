<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\IssuedCertificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentCertificateController extends Controller
{
    public function index(): View
    {
        $certificates = IssuedCertificate::query()
            ->where('student_id', auth()->id())
            ->with('course')
            ->orderByDesc('issued_at')
            ->paginate(15);

        return view('student.certificates.index', compact('certificates'));
    }

    public function download(IssuedCertificate $certificate): StreamedResponse|RedirectResponse
    {
        $this->authorize('download', $certificate);

        if (! Storage::disk('public')->exists($certificate->pdf_path)) {
            return back()->with('warning', __('Certificate file missing.'));
        }

        return Storage::disk('public')->download($certificate->pdf_path, $certificate->certificate_number.'.pdf');
    }
}
