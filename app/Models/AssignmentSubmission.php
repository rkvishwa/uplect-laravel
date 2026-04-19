<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmission extends Model
{
    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_GRADED = 'graded';

    public const STATUS_RETURNED = 'returned';

    protected $fillable = [
        'course_assignment_id',
        'student_id',
        'submission_text',
        'submission_file_path',
        'submitted_at',
        'marks',
        'graded_by',
        'graded_at',
        'feedback',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'graded_at' => 'datetime',
            'marks' => 'decimal:2',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(CourseAssignment::class, 'course_assignment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
