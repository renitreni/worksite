<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LocationSuggestion;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\Area;
use Illuminate\Support\Facades\DB;

class LocationSuggestionController extends Controller
{
    public function index(Request $request)
{
    $q = trim((string) $request->query('q', ''));
    $status = trim((string) $request->query('status', ''));

    $suggestions = LocationSuggestion::query()
        ->when($q !== '', function ($qr) use ($q) {
            $qr->where(function ($sub) use ($q) {
                $sub->where('country', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('area', 'like', "%{$q}%");
            });
        })
        ->when($status !== '', function ($qr) use ($status) {
            $qr->where('status', $status);
        })
        ->orderByDesc('count')
        ->latest('updated_at')
        ->paginate(10)
        ->withQueryString();

    return view('adminpage.contents.location_suggestions.index', compact('suggestions', 'q', 'status'));
}

    public function update(Request $request, LocationSuggestion $suggestion)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,ignored'],
        ]);

        try {
            $suggestion->update(['status' => $data['status']]);
            return back()->with('success', 'Suggestion updated.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to update suggestion.');
        }
    }

    public function destroy(LocationSuggestion $suggestion)
    {
        try {
            $suggestion->delete();
            return back()->with('success', 'Suggestion deleted.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to delete suggestion.');
        }
    }

    public function approve(LocationSuggestion $suggestion)
{
    try {
        DB::transaction(function () use ($suggestion) {

            $countryName = trim((string) $suggestion->country);
            $cityName = trim((string) ($suggestion->city ?? ''));
            $areaName = trim((string) ($suggestion->area ?? ''));

            if ($countryName === '') {
                throw new \RuntimeException('Suggestion country is empty.');
            }

            // Countries.name is unique (per your schema screenshot)
            $country = Country::firstOrCreate(
                ['name' => $countryName],
                [
                    'code' => null,
                    'image' => null,
                    'is_active' => 1,
                    'sort_order' => 0,
                ]
            );

            $city = null;
            if ($cityName !== '') {
                $city = City::firstOrCreate(
                    ['country_id' => $country->id, 'name' => $cityName],
                    [
                        'is_active' => 1,
                        'sort_order' => 0,
                    ]
                );
            }

            if ($city && $areaName !== '') {
                Area::firstOrCreate(
                    ['city_id' => $city->id, 'name' => $areaName],
                    [
                        'is_active' => 1,
                        'sort_order' => 0,
                    ]
                );
            }

            $suggestion->status = 'approved';
            $suggestion->save();
        });

        return back()->with('success', 'Suggestion approved and added to Locations.');
    } catch (\Throwable $e) {
        return back()->with('error', 'Failed to approve suggestion.');
    }
}
}