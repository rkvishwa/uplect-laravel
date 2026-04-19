<?php

namespace App\Http\Requests\Admin;

use App\Models\CourseAssignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTimelineCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $type = $this->input('card_type');

        $base = [
            'card_type' => ['required', Rule::in(['session', 'assignment', 'certificate'])],
            'scheduled_date' => ['required', 'date'],
            'scheduled_start_time' => ['nullable', 'date_format:H:i'],
            'scheduled_end_time' => ['nullable', 'date_format:H:i'],
            'is_makeup' => ['sometimes', 'boolean'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:20000'],
        ];

        if ($type === 'session') {
            return $base + [
                'resource_link' => ['nullable', 'url', 'max:2048'],
            ];
        }

        if ($type === 'assignment') {
            return $base + [
                'submission_type' => ['required', Rule::in([CourseAssignment::SUBMISSION_FILE, CourseAssignment::SUBMISSION_TEXT])],
                'pass_mark' => ['required', 'integer', 'min:0', 'max:1000'],
                'max_mark' => ['required', 'integer', 'min:1', 'max:1000'],
                'is_required_for_certification' => ['sometimes', 'boolean'],
                'due_at' => ['nullable', 'date'],
                'instructions' => ['nullable', 'string', 'max:20000'],
                'max_file_size_mb' => ['nullable', 'integer', 'min:1', 'max:50'],
            ];
        }

        if ($type === 'certificate') {
            return $base;
        }

        return $base;
    }
}
