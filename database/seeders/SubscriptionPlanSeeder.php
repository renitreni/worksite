<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'code' => 'standard',
                'name' => 'Standard Plan',
                'price' => 350,
                'sort_order' => 1,
            ],
            [
                'code' => 'gold',
                'name' => 'Gold Plan',
                'price' => 550,
                'sort_order' => 2,
            ],
            [
                'code' => 'platinum',
                'name' => 'Platinum Plan',
                'price' => 750,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['code' => $plan['code']],
                $plan
            );
        }
    }
}