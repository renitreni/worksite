<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industry;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $data = [

            'Construction & Skilled Trades' => [
                'Welder',
                'Electrician',
                'Tile Setter',
                'Painter',
                'Plumber',
                'Carpenter',
            ],

            'Manufacturing & Production' => [
                'Machine Operator',
                'Production Worker',
                'Assembly Line Worker',
                'Warehouse Worker',
            ],

            'Healthcare & Medical Services' => [
                'Registered Nurse',
                'Caregiver',
                'Nursing Assistant',
                'Medical Technologist',
            ],

            'Hospitality & Tourism' => [
                'Hotel Housekeeper',
                'Waiter / Waitress',
                'Bartender',
                'Front Desk Officer',
            ],

            'Domestic Work & Household Services' => [
                'Domestic Helper',
                'Nanny',
                'Housekeeper',
                'Family Driver',
            ],

            'Transportation, Logistics & Warehousing' => [
                'Truck Driver',
                'Bus Driver',
                'Delivery Driver',
                'Forklift Operator',
            ],

            'Maritime & Seafaring' => [
                'Able Seaman',
                'Oiler',
                'Bosun',
                'Motorman',
            ],

            'Engineering (Land & Marine)' => [
                'Civil Engineer',
                'Mechanical Engineer',
                'Marine Engineer',
            ],

            'Information Technology (IT) & Technical Support' => [
                'IT Support Specialist',
                'Network Technician',
                'Software Developer',
            ],

            'Retail, Sales & Customer Service' => [
                'Sales Associate',
                'Cashier',
                'Customer Service Representative',
            ],

            'Cruise Ship Hospitality' => [
                'Cruise Waiter',
                'Cruise Bartender',
                'Cabin Steward',
            ],

            'Oil, Gas & Offshore Services' => [
                'Offshore Technician',
                'Driller',
                'Rig Mechanic',
            ],
        ];

        foreach ($data as $industryName => $skills) {

            $industry = Industry::where('name', $industryName)->first();

            if (!$industry) continue;

            foreach ($skills as $index => $skill) {
                Skill::updateOrCreate(
                    [
                        'name' => $skill,
                        'industry_id' => $industry->id,
                    ],
                    [
                        'is_active' => true,
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }
    }
}