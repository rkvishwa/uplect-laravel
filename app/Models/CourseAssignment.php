<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CourseAssignment extends Model
{
    public const SUBMISSION_FILE = 'file';

    public const SUBMISSION_TEXT = 'text';

    protected $fillable = [
        'title',
        'description',
        'submission_type',
        'allowed_mime_types',
        'max_file_size_mb',
        'pass_mark',
        'max_mark',
        'is_required_for_certification',
        'due_at',
        'instructions',
    ];

    protected function casts(): array
    {
        return [
            'allowed_mime_types' => 'array',
            'is_required_for_certification' => 'boolean',
            'due_at' => 'datetime',
        ];
    }

    public function timelineItem(): MorphOne
    {
        return $this->morphOne(TimelineItem::class, 'cardable');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'course_assignment_id');
    }
}
