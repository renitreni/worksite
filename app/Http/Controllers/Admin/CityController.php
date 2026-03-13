<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Services\Admin\AdminCityService;

class CityController extends Controller
{
    public function __construct(
        private AdminCityService $cityService
    ) {}

    public function index(Request $request, Country $country)
    {
        $data = $this->cityService->getCities($request, $country);

        return view(
            'adminpage.contents.locations.cities.index',
            $data
        );
    }

    public function edit(Country $country, City $city)
    {
        $this->cityService->validateCity($country, $city);

        return view(
            'adminpage.contents.locations.cities.edit',
            compact('country', 'city')
        );
    }

    public function store(Request $request, Country $country)
    {
        $this->cityService->createCity($request, $country);

        return redirect()
            ->route('admin.locations.cities.index', $country)
            ->with('success', 'City created.');
    }

    public function update(Request $request, Country $country, City $city)
    {
        $this->cityService->updateCity($request, $country, $city);

        return redirect()
            ->route('admin.locations.cities.index', $country)
            ->with('success', 'City updated.');
    }

    public function destroy(Country $country, City $city)
    {
        $this->cityService->deleteCity($country, $city);

        return back()->with('success', 'City deleted.');
    }

    public function updateMeta(Request $request, Country $country, City $city)
    {
        $this->cityService->updateMeta($request, $country, $city);

        return back()->with('success', 'City updated.');
    }
}