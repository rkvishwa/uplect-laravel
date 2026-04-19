<?php

namespace App\Http\Requests\Lecturer;

use App\Models\AssignmentSubmission;
use Illuminate\Foundation\Http\FormRequest;

class GradeSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $submission = $this->route('submission');

        return $this->user()?->can('grade', $submission) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'marks' => ['required', 'numeric', 'min:0'],
            'feedback' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
