<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoomMeeting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_session_id',
        'zoom_meeting_id',
        'topic',
        'start_at',
        'duration_minutes',
        'host_start_url',
        'shared_join_url',
        'password',
        'settings',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'settings' => 'array',
        ];
    }

    public function courseSession(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrants(): HasMany
    {
        return $this->hasMany(ZoomMeetingRegistrant::class, 'meeting_id');
    }
}
