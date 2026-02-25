<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industry;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            'Healthcare & Medical Services',
            'Construction & Skilled Trades',
            'Hospitality & Tourism',
            'Domestic Work & Household Services',
            'Manufacturing & Production',
            'Engineering (Land & Marine)',
            'Information Technology (IT) & Technical Support',
            'Retail, Sales & Customer Service',
            'Transportation, Logistics & Warehousing',
            'Maritime & Seafaring',
            'Cruise Ship Hospitality',
            'Oil, Gas & Offshore Services',
        ];

        foreach ($industries as $index => $industry) {
            Industry::updateOrCreate(
                ['name' => $industry],
                [
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}