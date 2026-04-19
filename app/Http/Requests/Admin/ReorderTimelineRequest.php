<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReorderTimelineRequest extends FormRequest
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
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'scheduled_date' => ['required', 'date'],
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['integer', 'exists:timeline_items,id'],
        ];
    }
}
