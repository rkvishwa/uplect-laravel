<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CourseSession extends Model
{
    protected $fillable = [
        'title',
        'description',
        'resource_link',
        'recording_url',
        'recording_added_at',
    ];

    protected function casts(): array
    {
        return [
            'recording_added_at' => 'datetime',
        ];
    }

    public function timelineItem(): MorphOne
    {
        return $this->morphOne(TimelineItem::class, 'cardable');
    }

    public function zoomMeeting(): HasOne
    {
        return $this->hasOne(ZoomMeeting::class, 'course_session_id');
    }
}
