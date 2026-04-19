<?php

namespace App\Http\Requests\Admin;

use App\Models\TimelineItem;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTimelineTimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $item = $this->route('timelineItem');
        if (! $item instanceof TimelineItem) {
            return false;
        }

        return $this->user()?->can('update', $item) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'scheduled_start_time' => ['required', 'date_format:H:i'],
            'scheduled_end_time' => ['required', 'date_format:H:i', 'after:scheduled_start_time'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $item = $this->route('timelineItem');
            if (! $item instanceof TimelineItem || ! $item->isSession()) {
                $v->errors()->add('timeline', __('Only session cards support time updates.'));
            }
        });
    }
}
