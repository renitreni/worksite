<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industry;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Domestic',
            'Caregiver',
            'Construction',
            'Factory',
            'Driver',
            'Hospitality',
            'Food',
            'Admin',
            'Beauty',
            'Maritime',
        ];

        foreach ($items as $i => $name) {
            Industry::firstOrCreate(
                ['name' => $name],
                ['is_active' => true, 'sort_order' => $i]
            );
        }
    }
}
