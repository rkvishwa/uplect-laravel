<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'category_id',
        'lecturer_id',
        'title',
        'slug',
        'description',
        'image_path',
        'day_of_week',
        'start_time',
        'end_time',
        'lecturer_payment_per_session_lkr',
        'student_total_fee_lkr',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'lecturer_payment_per_session_lkr' => 'decimal:2',
            'student_total_fee_lkr' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function timelineItems(): HasMany
    {
        return $this->hasMany(TimelineItem::class)->orderBy('scheduled_date')->orderBy('order_index');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function issuedCertificates(): HasMany
    {
        return $this->hasMany(IssuedCertificate::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * @return list<string>
     */
    public static function weekdays(): array
    {
        return ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    }

    public static function weekdayLabels(): array
    {
        return [
            'mon' => __('Monday'),
            'tue' => __('Tuesday'),
            'wed' => __('Wednesday'),
            'thu' => __('Thursday'),
            'fri' => __('Friday'),
            'sat' => __('Saturday'),
            'sun' => __('Sunday'),
        ];
    }

    public function hasBlockingEnrollments(): bool
    {
        return $this->enrollments()
            ->whereIn('status', [Enrollment::STATUS_ACTIVE, Enrollment::STATUS_COMPLETED])
            ->exists();
    }
}
