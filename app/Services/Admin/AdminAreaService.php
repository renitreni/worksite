<?php

namespace App\Services\Admin;

use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class AdminAreaService
{
    public function validateArea(Country $country, City $city, ?Area $area = null)
    {
        abort_unless($city->country_id === $country->id, 404);

        if ($area) {
            abort_unless($area->city_id === $city->id, 404);
        }
    }

    public function getAreas(Request $request, Country $country, City $city)
    {
        $this->validateArea($country, $city);

        $q = (string) $request->query('q', '');
        $active = $request->query('active', '');

        $query = Area::query()->where('city_id', $city->id);

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }

        if ($active !== '') {
            $query->where('is_active', (int) $active);
        }

        $areas = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return compact('country', 'city', 'areas', 'q', 'active');
    }

    public function createArea(Request $request, Country $country, City $city)
    {
        $this->validateArea($country, $city);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        Area::create([
            'city_id' => $city->id,
            'name' => $data['name'],
            'is_active' => (int) ($data['is_active'] ?? 0),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);
    }

    public function updateArea(Request $request, Country $country, City $city, Area $area)
    {
        $this->validateArea($country, $city, $area);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $area->update([
            'name' => $data['name'],
            'is_active' => (int) ($data['is_active'] ?? 0),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);
    }

    public function deleteArea(Country $country, City $city, Area $area)
    {
        $this->validateArea($country, $city, $area);

        $area->delete();
    }

    public function updateMeta(Request $request, Country $country, City $city, Area $area)
    {
        $this->validateArea($country, $city, $area);

        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $area->update([
            'is_active' => (int) $data['is_active'],
            'sort_order' => (int) $data['sort_order'],
        ]);
    }
}