<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(Request $request, Country $country, City $city)
{
    // Safety: make sure the city really belongs to the country
    abort_unless($city->country_id === $country->id, 404);

    $q = (string) $request->query('q', '');
    $active = $request->query('active', '');

    $query = Area::query()->where('city_id', $city->id);

    if ($q !== '') {
        $query->where('name', 'like', '%' . $q . '%');
    }

    if ($active !== '') {
        $query->where('is_active', (int) $active);
    }

    $areas = $query
        ->orderBy('sort_order')
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    return view('adminpage.contents.locations.areas.index', compact('country', 'city', 'areas', 'q', 'active'));
}

    public function edit(Country $country, City $city, Area $area)
    {
        if ((int) $city->country_id !== (int) $country->id) abort(404);
        if ((int) $area->city_id !== (int) $city->id) abort(404);

        return view('adminpage.contents.locations.areas.edit', compact('country', 'city', 'area'));
    }

    public function store(Request $request, Country $country, City $city)
    {
        if ((int) $city->country_id !== (int) $country->id) abort(404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            Area::create([
                'city_id' => $city->id,
                'name' => $data['name'],
                'is_active' => (int) ($data['is_active'] ?? 0),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ]);

            return redirect()->route('admin.locations.areas.index', [$country, $city])->with('success', 'Area created.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to create area.');
        }
    }

    public function update(Request $request, Country $country, City $city, Area $area)
    {
        if ((int) $city->country_id !== (int) $country->id) abort(404);
        if ((int) $area->city_id !== (int) $city->id) abort(404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            $area->update([
                'name' => $data['name'],
                'is_active' => (int) ($data['is_active'] ?? 0),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ]);

            return redirect()->route('admin.locations.areas.index', [$country, $city])->with('success', 'Area updated.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to update area.');
        }
    }

    public function destroy(Country $country, City $city, Area $area)
    {
        if ((int) $city->country_id !== (int) $country->id) abort(404);
        if ((int) $area->city_id !== (int) $city->id) abort(404);

        try {
            $area->delete();
            return back()->with('success', 'Area deleted.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to delete area.');
        }
    }

    public function updateMeta(Request $request, Country $country, City $city, Area $area)
{
    if ((int) $city->country_id !== (int) $country->id) abort(404);
    if ((int) $area->city_id !== (int) $city->id) abort(404);

    $data = $request->validate([
        'is_active' => ['required', 'boolean'],
        'sort_order' => ['required', 'integer', 'min:0'],
    ]);

    try {
        $area->update([
            'is_active' => (int) $data['is_active'],
            'sort_order' => (int) $data['sort_order'],
        ]);

        return back()->with('success', 'Area updated.');
    } catch (\Throwable $e) {
        return back()->with('error', 'Failed to update area.');
    }
}
}