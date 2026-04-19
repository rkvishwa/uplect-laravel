<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimelineItem extends Model
{
    use SoftDeletes;

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'course_id',
        'scheduled_date',
        'scheduled_start_time',
        'scheduled_end_time',
        'order_index',
        'cardable_type',
        'cardable_id',
        'status',
        'cancellation_reason',
        'is_makeup',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'is_makeup' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function cardable(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isSession(): bool
    {
        return $this->cardable_type === CourseSession::class;
    }

    public function isAssignment(): bool
    {
        return $this->cardable_type === CourseAssignment::class;
    }

    public function isCertificate(): bool
    {
        return $this->cardable_type === CourseCertificate::class;
    }
}
