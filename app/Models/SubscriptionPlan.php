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
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
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

    public function featureValues()
    {
        return $this->hasMany(\App\Models\PlanFeature::class, 'plan_id')->with('definition');
    }

    public function feature(string $key, $default = null)
    {
        // Make sure featureValues is loaded (eager-load in currentPlan())
        $row = $this->featureValues->first(fn($pf) => $pf->definition?->key === $key);

        if (!$row) return $default;

        // value stored as JSON can be scalar or array
        return $row->value ?? $row->definition?->default_value ?? $default;
    }
}
