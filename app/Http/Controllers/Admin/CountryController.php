<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Support\HandlesPublicImage;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use HandlesPublicImage;

    private const IMG_DIR = 'countries';

   public function index(Request $request)
{
    $q = (string) $request->query('q', '');
    $active = $request->query('active', '');

    $query = Country::query();

    if ($q !== '') {
        $query->where('name', 'like', '%' . $q . '%');
    }

    // IMPORTANT: use !== '' so "0" still works
    if ($active !== '') {
        $query->where('is_active', (int) $active);
    }

    $countries = $query
        ->orderBy('sort_order')
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    return view('adminpage.contents.locations.countries.index', compact('countries', 'q', 'active'));
}

    public function edit(Country $country)
    {
        return view('adminpage.contents.locations.countries.edit', compact('country'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:countries,name'],
            'code' => ['nullable', 'string', 'max:5'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        try {
            $country = new Country();
            $country->name = $data['name'];
            $country->code = $data['code'] ?? null;
            $country->is_active = (int) ($data['is_active'] ?? 0);
            $country->sort_order = (int) ($data['sort_order'] ?? 0);

            if ($request->hasFile('image')) {
                $country->image = $this->storePublicImage($request->file('image'), self::IMG_DIR);
            }

            $country->save();

            return redirect()->route('admin.locations.countries.index')->with('success', 'Country created.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to create country.');
        }
    }

    public function update(Request $request, Country $country)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:countries,name,' . $country->id],
            'code' => ['nullable', 'string', 'max:5'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        try {
            $country->name = $data['name'];
            $country->code = $data['code'] ?? null;
            $country->is_active = (int) ($data['is_active'] ?? 0);
            $country->sort_order = (int) ($data['sort_order'] ?? 0);

            if ($request->hasFile('image')) {
                $old = $country->image;
                $country->image = $this->storePublicImage($request->file('image'), self::IMG_DIR);
                $this->deletePublicImage($old);
            }

            $country->save();

            return redirect()->route('admin.locations.countries.index')->with('success', 'Country updated.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Failed to update country.');
        }
    }

    public function destroy(Country $country)
    {
        try {
            // safe default: block if has cities
            if (method_exists($country, 'cities') && $country->cities()->exists()) {
                return back()->with('error', 'Cannot delete this country because it has cities.');
            }

            $this->deletePublicImage($country->image ?? null);
            $country->delete();

            return back()->with('success', 'Country deleted.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to delete country.');
        }
    }
    public function updateMeta(Request $request, Country $country)
{
    $validated = $request->validate([
        'sort_order' => ['required', 'integer', 'min:0'],
        'is_active'  => ['required', 'in:0,1'],
    ]);

    try {
        $country->update([
            'sort_order' => (int) $validated['sort_order'],
            'is_active'  => (int) $validated['is_active'],
        ]);

        return back()->with('success', 'Country updated.');
    } catch (\Throwable $e) {
        return back()->with('error', 'Failed to update country.');
    }
}
}