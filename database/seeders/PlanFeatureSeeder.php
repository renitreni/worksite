<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\FeatureDefinition;
use App\Models\PlanFeature;

class PlanFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $plans = SubscriptionPlan::all()->keyBy('code');
        $features = FeatureDefinition::all()->keyBy('key');

        $matrix = [

            'standard' => [
                'job_limit_active' => 2,
                'candidate_profile_views_per_day' => 5,
                'candidate_info_level' => 'basic_preview',
                'cv_access' => 'none',
                'direct_messaging' => false,
                'advanced_candidate_filters' => false,
                'analytics_level' => 'basic',
                'search_visibility' => 'normal',
            ],

            'gold' => [
                'job_limit_active' => 10,
                'candidate_profile_views_per_day' => null,
                'candidate_info_level' => 'expanded',
                'cv_access' => 'preview',
                'direct_messaging' => false,
                'advanced_candidate_filters' => true,
                'analytics_level' => 'advanced',
                'search_visibility' => 'featured',
            ],

            'platinum' => [
                'job_limit_active' => null,
                'candidate_profile_views_per_day' => null,
                'candidate_info_level' => 'full',
                'cv_access' => 'download',
                'direct_messaging' => true,
                'advanced_candidate_filters' => true,
                'analytics_level' => 'enterprise',
                'search_visibility' => 'priority',
            ],
        ];

        foreach ($matrix as $planCode => $values) {
            $plan = $plans[$planCode];

            foreach ($values as $featureKey => $value) {
                PlanFeature::updateOrCreate(
                    [
                        'plan_id' => $plan->id,
                        'feature_definition_id' => $features[$featureKey]->id,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }
    }
}