<?php

namespace App\Http\Requests\Lecturer;

use App\Models\CourseAssignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLecturerAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $assignment = $this->route('assignment');

        return $this->user()?->can('update', $assignment) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'instructions' => ['nullable', 'string', 'max:20000'],
            'pass_mark' => ['required', 'integer', 'min:0', 'max:1000'],
            'submission_type' => ['required', Rule::in([CourseAssignment::SUBMISSION_FILE, CourseAssignment::SUBMISSION_TEXT])],
        ];
    }
}
