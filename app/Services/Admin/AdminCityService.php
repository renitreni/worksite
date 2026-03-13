<?php

namespace App\Services\Admin;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class AdminCityService
{
    public function validateCity(Country $country, City $city)
    {
        abort_unless($city->country_id === $country->id, 404);
    }

    public function getCities(Request $request, Country $country)
    {
        $q = (string) $request->query('q', '');
        $active = $request->query('active', '');

        $query = City::query()
            ->where('country_id', $country->id);

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }

        if ($active !== '') {
            $query->where('is_active', (int) $active);
        }

        $cities = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return compact('country', 'cities', 'q', 'active');
    }

    public function createCity(Request $request, Country $country)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        City::create([
            'country_id' => $country->id,
            'name' => $data['name'],
            'is_active' => (int) ($data['is_active'] ?? 0),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);
    }

    public function updateCity(Request $request, Country $country, City $city)
    {
        $this->validateCity($country, $city);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $city->update([
            'name' => $data['name'],
            'is_active' => (int) ($data['is_active'] ?? 0),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);
    }

    public function deleteCity(Country $country, City $city)
    {
        $this->validateCity($country, $city);

        if (method_exists($city, 'areas') && $city->areas()->exists()) {
            abort(400, 'Cannot delete city because it has areas.');
        }

        $city->delete();
    }

    public function updateMeta(Request $request, Country $country, City $city)
    {
        $this->validateCity($country, $city);

        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $city->update([
            'is_active' => (int) $data['is_active'],
            'sort_order' => (int) $data['sort_order'],
        ]);
    }
}