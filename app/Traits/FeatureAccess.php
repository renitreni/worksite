<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait FeatureAccess
{
    public function activeFeatures(): Collection
    {
        if (method_exists($this, 'plan') && $this->plan) {
            $subscription = $this;
        } elseif (method_exists($this, 'activeSubscription') && $this->activeSubscription) {
            $subscription = $this->activeSubscription()->with('plan.featureValues.definition')->first();
        } else {
            return collect();
        }

        if (!$subscription || !$subscription->isActive() || !$subscription->plan) {
            return collect();
        }

        return $subscription->plan->featureValues
            ->filter(fn($fv) => $fv->definition)
            ->keyBy(fn($fv) => $fv->definition->key)
            ->map(fn($fv) => $fv->value ?? $fv->definition->default_value);
    }

    public function canFeature(string $key, $default = false)
    {
        return $this->activeFeatures()->get($key, $default);
    }
}