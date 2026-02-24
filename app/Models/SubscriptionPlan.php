<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'price',
        'features',
        'is_active',
    ];

    protected $casts = [
        'features'  => 'array',
        'is_active' => 'boolean',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'plan_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(EmployerSubscription::class, 'plan_id');
    }

    // Optional helper: safe read from features JSON
    public function feature(string $key, $default = null)
    {
        return data_get($this->features, $key, $default);
    }
}