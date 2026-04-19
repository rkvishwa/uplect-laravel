<?php

namespace App\Http\Requests\Admin;

use App\Models\TimelineItem;
use Illuminate\Foundation\Http\FormRequest;

class CancelTimelineItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $item = $this->route('timelineItem');
        if (! $item instanceof TimelineItem) {
            return false;
        }

        return $this->user()?->can('cancel', $item) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'cancellation_reason' => ['required', 'string', 'max:5000'],
        ];
    }
}
