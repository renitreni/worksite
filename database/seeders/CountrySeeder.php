<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('countries') as $country) {

            Country::updateOrCreate(
                ['code' => strtoupper($country['code'])],
                [
                    'name' => $country['name'],
                    'is_active' => 1,
                    'sort_order' => 0,
                ]
            );
        }
    }
}