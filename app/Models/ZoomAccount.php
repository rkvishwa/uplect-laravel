<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoomAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'account_id',
        'client_id',
        'client_secret',
        'host_user_id',
        'timezone',
        'waiting_room',
        'is_default',
        'is_active',
    ];

    protected $hidden = [
        'client_secret',
    ];

    protected function casts(): array
    {
        return [
            'client_secret' => 'encrypted',
            'waiting_room' => 'boolean',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(ZoomMeeting::class, 'zoom_account_id');
    }

    public static function default(): ?self
    {
        return static::query()
            ->active()
            ->where('is_default', true)
            ->first()
            ?? static::query()->active()->orderBy('name')->first();
    }
}
