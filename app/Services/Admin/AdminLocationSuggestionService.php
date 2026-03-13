<?php

namespace App\Services\Admin;

use App\Models\LocationSuggestion;
use App\Models\Country;
use App\Models\City;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLocationSuggestionService
{
    public function getSuggestions(Request $request): array
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
            ->when($status !== '', fn($qr) => $qr->where('status', $status))
            ->orderByDesc('count')
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return compact('suggestions','q','status');
    }

    public function updateStatus(Request $request, LocationSuggestion $suggestion): void
    {
        $data = $request->validate([
            'status' => ['required','in:pending,approved,ignored']
        ]);

        $suggestion->update([
            'status' => $data['status']
        ]);
    }

    public function deleteSuggestion(LocationSuggestion $suggestion): void
    {
        $suggestion->delete();
    }

    public function approveSuggestion(LocationSuggestion $suggestion): void
    {
        DB::transaction(function () use ($suggestion) {

            $countryName = trim((string) $suggestion->country);
            $cityName = trim((string) ($suggestion->city ?? ''));
            $areaName = trim((string) ($suggestion->area ?? ''));

            if ($countryName === '') {
                throw new \RuntimeException('Suggestion country is empty.');
            }

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
                    [
                        'country_id' => $country->id,
                        'name' => $cityName
                    ],
                    [
                        'is_active' => 1,
                        'sort_order' => 0,
                    ]
                );
            }

            if ($city && $areaName !== '') {

                Area::firstOrCreate(
                    [
                        'city_id' => $city->id,
                        'name' => $areaName
                    ],
                    [
                        'is_active' => 1,
                        'sort_order' => 0,
                    ]
                );

            }

            $suggestion->update([
                'status' => 'approved'
            ]);
        });
    }
}