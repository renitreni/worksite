<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerSubscription extends Model
{
    public const STATUS_PENDING   = 'pending_activation';
    public const STATUS_ACTIVE    = 'active';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_EXPIRED   = 'expired';

    protected $fillable = [
        'employer_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'activated_by_admin_id',
        'activated_at',
        'suspended_by_admin_id',
        'suspended_at',
        'suspend_reason',
    ];

    protected $casts = [
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
        'activated_at' => 'datetime',
        'suspended_at' => 'datetime',
    ];
  
   public function employerProfile()
    {
        return $this->belongsTo(EmployerProfile::class);
   }
    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function activatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'activated_by_admin_id');
    }

    public function suspendedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'suspended_by_admin_id');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
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