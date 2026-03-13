<?php

namespace App\Services\Employer;

use App\Models\Industry;
use App\Models\Skill;
use App\Models\Country;
use App\Models\City;
use App\Models\Area;
use Illuminate\Support\Facades\Schema;

class EmployerTaxonomyService
{
    public function taxonomies(): array
    {
        $industries = Industry::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id','name']);

        $skills = [];

        $countries = collect(config('countries', []))
            ->pluck('name')
            ->filter()
            ->values()
            ->toArray();

        $cities = [];
        $areas = [];

        $currencies = config('currencies', []);
        asort($currencies);

        return compact(
            'industries',
            'skills',
            'countries',
            'cities',
            'areas',
            'currencies'
        );
    }

    public function skillsByIndustry($industry)
    {
        return Skill::query()
            ->where('industry_id', $industry->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id','name']);
    }

    public function citiesByCountry(string $countryName)
    {
        $country = Country::where('name', $countryName)->first();

        if (!$country) {
            return [];
        }

        $query = City::where('country_id', $country->id);

        if (Schema::hasColumn('cities','is_active')) {
            $query->where('is_active', true);
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name')
            ->values();
    }

    public function areasByCity(string $countryName, string $cityName)
    {
        $country = Country::where('name',$countryName)->first();

        if (!$country) {
            return [];
        }

        $city = City::where('country_id',$country->id)
            ->where('name',$cityName)
            ->first();

        if (!$city) {
            return [];
        }

        $query = Area::where('city_id',$city->id);

        if (Schema::hasColumn('areas','is_active')) {
            $query->where('is_active',true);
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name')
            ->values();
    }
}