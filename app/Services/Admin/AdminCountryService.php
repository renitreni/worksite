<?php

namespace App\Services\Admin;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Support\HandlesPublicImage;

class AdminCountryService
{
    use HandlesPublicImage;

    private const IMG_DIR = 'countries';

    public function getCountries(Request $request): array
    {
        $q = (string) $request->query('q', '');
        $active = $request->query('active', '');

        $query = Country::query();

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }

        if ($active !== '') {
            $query->where('is_active', (int) $active);
        }

        $countries = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return compact('countries', 'q', 'active');
    }

    public function createCountry(Request $request): void
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:countries,name'],
            'code' => ['nullable', 'string', 'max:5'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $country = new Country();

        $country->name = $data['name'];
        $country->code = $data['code'] ?? null;
        $country->is_active = (int) ($data['is_active'] ?? 0);
        $country->sort_order = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            $country->image = $this->storePublicImage(
                $request->file('image'),
                self::IMG_DIR
            );
        }

        $country->save();
    }

    public function updateCountry(Request $request, Country $country): void
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:countries,name,' . $country->id],
            'code' => ['nullable', 'string', 'max:5'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $country->name = $data['name'];
        $country->code = $data['code'] ?? null;
        $country->is_active = (int) ($data['is_active'] ?? 0);
        $country->sort_order = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {

            $old = $country->image;

            $country->image = $this->storePublicImage(
                $request->file('image'),
                self::IMG_DIR
            );

            $this->deletePublicImage($old);
        }

        $country->save();
    }

    public function deleteCountry(Country $country): void
    {
        if ($country->cities()->exists()) {
            abort(400, 'Cannot delete this country because it has cities.');
        }

        $this->deletePublicImage($country->image ?? null);

        $country->delete();
    }

    public function updateMeta(Request $request, Country $country): void
    {
        $data = $request->validate([
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'in:0,1'],
        ]);

        $country->update([
            'sort_order' => (int) $data['sort_order'],
            'is_active' => (int) $data['is_active'],
        ]);
    }
}