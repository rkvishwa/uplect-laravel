<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CourseCertificate extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    public function timelineItem(): MorphOne
    {
        return $this->morphOne(TimelineItem::class, 'cardable');
    }
}
