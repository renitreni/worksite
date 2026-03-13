<?php

namespace App\Services\Employer;

use Illuminate\Http\Request;
use App\Models\LocationSuggestion;

class EmployerLocationService
{
    public function normalizeCityArea(Request $request): array
    {
        $city = $request->input('city');
        $area = $request->input('area');

        if ($city === '__custom__') {
            $city = trim((string)$request->input('city_custom',''));
        }

        if ($area === '__custom__') {
            $area = trim((string)$request->input('area_custom',''));
        }

        $city = $city !== '' ? $city : null;
        $area = $area !== '' ? $area : null;

        return [$city,$area];
    }

    public function maybeCreateLocationSuggestion(
        Request $request,
        string $country,
        ?string $city,
        ?string $area
    ): void {

        $usedCustom =
            $request->input('city') === '__custom__' ||
            $request->input('area') === '__custom__';

        if (!$usedCustom) {
            return;
        }

        if (!$city && !$area) {
            return;
        }

        $suggestion = LocationSuggestion::firstOrCreate(
            [
                'country'=>$country,
                'city'=>$city,
                'area'=>$area
            ],
            [
                'count'=>0,
                'status'=>'pending'
            ]
        );

        $suggestion->increment('count');

        if ($suggestion->status === 'ignored') {
            $suggestion->update(['status'=>'pending']);
        }
    }
}