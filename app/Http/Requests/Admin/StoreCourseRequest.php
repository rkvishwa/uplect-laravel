<?php

namespace App\Http\Requests\Admin;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
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
            'category_id' => ['nullable', 'exists:categories,id'],
            'lecturer_id' => ['required', 'exists:users,id', Rule::exists('users', 'id')->where('role', User::ROLE_LECTURER)],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:courses,slug'],
            'description' => ['nullable', 'string', 'max:20000'],
            'image' => ['nullable', 'image', 'max:4096'],
            'day_of_week' => ['required', Rule::in(Course::weekdays())],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'lecturer_payment_per_session_lkr' => ['required', 'numeric', 'min:0'],
            'student_total_fee_lkr' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in([Course::STATUS_ACTIVE, Course::STATUS_INACTIVE])],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('slug') && $this->filled('title')) {
            $this->merge([
                'slug' => Str::slug((string) $this->input('title')),
            ]);
        }
    }
}
