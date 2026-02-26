<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployerSubscription extends Model
{
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_EXPIRED  = 'expired';
    public const STATUS_CANCELED = 'canceled';

    protected $fillable = [
        'employer_profile_id',
        'plan_id',
        'subscription_status',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function employerProfile(): BelongsTo
    {
        return $this->belongsTo(EmployerProfile::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id')->withTrashed();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'subscription_id');
    }

    public function isActive(): bool
    {
        return $this->subscription_status === self::STATUS_ACTIVE
            && (!$this->ends_at || now()->lessThanOrEqualTo($this->ends_at));
    }

    public function isExpired(): bool
    {
        return $this->ends_at && now()->greaterThan($this->ends_at);
    }

    public function daysLeft(): ?int
    {
        if (!$this->ends_at) return null;
        return now()->startOfDay()->diffInDays($this->ends_at->startOfDay(), false);
    }
}
