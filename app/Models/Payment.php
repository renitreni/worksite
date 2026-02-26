<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const STATUS_PENDING   = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED    = 'failed';

    protected $fillable = [
        'employer_id',
        'plan_id',
        'subscription_id',
        'amount',
        'method',
        'status',
        'reference',
        'proof_path',
        'verified_by_admin_id',
        'verified_at',
        'fail_reason',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(EmployerSubscription::class, 'subscription_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_admin_id');
    }
}
