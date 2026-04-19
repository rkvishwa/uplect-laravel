<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    public const PAYMENT_PAYHERE = 'payhere';

    public const PAYMENT_BANK_TRANSFER = 'bank_transfer';

    public const PAYMENT_PENDING = 'pending';

    public const PAYMENT_PAID = 'paid';

    public const PAYMENT_DECLINED = 'declined';

    public const STATUS_PENDING = 'pending';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_DECLINED = 'declined';

    public const STATUS_DROPPED = 'dropped';

    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'course_id',
        'student_id',
        'payment_method',
        'payment_status',
        'payment_reference',
        'bank_slip_path',
        'amount_lkr',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'decline_reason',
        'enrolled_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount_lkr' => 'decimal:2',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'enrolled_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
