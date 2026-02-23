<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request, Country $country)
{
    $q = (string) $request->query('q', '');
    $active = $request->query('active', '');

    $query = City::query()->where('country_id', $country->id);

    if ($q !== '') {
        $query->where('name', 'like', '%' . $q . '%');
    }

    if ($active !== '') {
        $query->where('is_active', (int) $active);
    }

    $cities = $query
        ->orderBy('sort_order')
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    return view('adminpage.contents.locations.cities.index', compact('country', 'cities', 'q', 'active'));
}

    public function edit(Country $country, City $city)
    {
        if ((int) $city->country_id !== (int) $country->id) abort(404);
        return view('adminpage.contents.locations.cities.edit', compact('country', 'city'));
    }

    public function store(Request $request, Country $country)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            City::create([
                'country_id' => $country->id,
                'name' => $data['name'],
                'is_active' => (int) ($data['is_active'] ?? 0),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ]);

            return redirect()->route('admin.locations.cities.index', $country)->with('success', 'City created.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to create city.');
        }
    }

    public function update(Request $request, Country $country, City $city)
    {
        if ((int) $city->country_id !== (int) $country->id) abort(404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            $city->update([
                'name' => $data['name'],
                'is_active' => (int) ($data['is_active'] ?? 0),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ]);

            return redirect()->route('admin.locations.cities.index', $country)->with('success', 'City updated.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to update city.');
        }
    }

    public function destroy(Country $country, City $city)
    {
        if ((int) $city->country_id !== (int) $country->id) abort(404);

        try {
            if (method_exists($city, 'areas') && $city->areas()->exists()) {
                return back()->with('error', 'Cannot delete this city because it has areas.');
            }

            $city->delete();
            return back()->with('success', 'City deleted.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to delete city.');
        }
    }

    public function updateMeta(Request $request, Country $country, City $city)
{
    if ((int) $city->country_id !== (int) $country->id) abort(404);

    $data = $request->validate([
        'is_active' => ['required', 'boolean'],
        'sort_order' => ['required', 'integer', 'min:0'],
    ]);

    try {
        $city->update([
            'is_active' => (int) $data['is_active'],
            'sort_order' => (int) $data['sort_order'],
        ]);

        return back()->with('success', 'City updated.');
    } catch (\Throwable $e) {
        return back()->with('error', 'Failed to update city.');
    }
}

}