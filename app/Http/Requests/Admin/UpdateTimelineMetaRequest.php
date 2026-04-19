<?php

namespace App\Http\Requests\Admin;

use App\Models\TimelineItem;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTimelineMetaRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:20000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $item = $this->route('timelineItem');
            if (! $item instanceof TimelineItem || ! $item->isSession()) {
                $v->errors()->add('timeline', __('Only session cards support this update.'));
            }
        });
    }
}
