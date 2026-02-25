<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        SubscriptionPlan::updateOrCreate(['code' => 'STANDARD'], [
            'name' => 'Standard Plan',
            'price' => 350,
            'features' => json_encode([
                'max_jobs' => 2,
                'candidate_views_per_day' => 5,
                'analytics' => 'basic',
                'full_profile_access' => false,
            ]),
        ]);

        SubscriptionPlan::updateOrCreate(['code' => 'GOLD'], [
            'name' => 'Gold Plan',
            'price' => 550,
            'features' => json_encode([
                'max_jobs' => 10,
                'candidate_views_per_day' => -1, // unlimited
                'analytics' => 'advanced',
                'full_profile_access' => true,
            ]),
        ]);

        SubscriptionPlan::updateOrCreate(['code' => 'PLATINUM'], [
            'name' => 'Platinum Plan',
            'price' => 750,
            'features' => json_encode([
                'max_jobs' => -1, // unlimited
                'candidate_views_per_day' => -1,
                'analytics' => 'full',
                'full_profile_access' => true,
            ]),
        ]);
    }
}