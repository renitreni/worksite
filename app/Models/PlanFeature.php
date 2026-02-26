<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanFeature extends Model
{
    protected $fillable = [
        'plan_id',
        'feature_definition_id',
        'value',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(FeatureDefinition::class, 'feature_definition_id');
    }
}