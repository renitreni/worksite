<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeatureDefinition extends Model
{
    protected $fillable = [
        'key','label','type','options','default_value',
        'is_core','is_active','sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'default_value' => 'array',
        'is_core' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function planValues(): HasMany
    {
        return $this->hasMany(PlanFeature::class, 'feature_definition_id');
    }
}