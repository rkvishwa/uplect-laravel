<?php

namespace App\Http\Requests\Student;

use App\Models\CourseAssignment;
use Illuminate\Foundation\Http\FormRequest;

class SubmitAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isStudent() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $assignment = $this->route('assignment');
        if (! $assignment instanceof CourseAssignment) {
            return [];
        }

        if ($assignment->submission_type === CourseAssignment::SUBMISSION_FILE) {
            return [
                'file' => ['required', 'file', 'max:'.((int) $assignment->max_file_size_mb * 1024)],
            ];
        }

        return [
            'submission_text' => ['required', 'string', 'max:50000'],
        ];
    }
}
