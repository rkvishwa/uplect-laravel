<?php

namespace App\Http\Requests\Admin;

use App\Models\TimelineItem;
use Illuminate\Foundation\Http\FormRequest;

class TimelineRecordingRequest extends FormRequest
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
            'recording_url' => ['required', 'url', 'max:2048'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $item = $this->route('timelineItem');
            if (! $item instanceof TimelineItem || ! $item->isSession()) {
                $v->errors()->add('timeline', __('Only sessions support recordings.'));
            }
        });
    }
}
