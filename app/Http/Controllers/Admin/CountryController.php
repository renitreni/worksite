<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Services\Admin\AdminCountryService;

class CountryController extends Controller
{
    public function __construct(
        private AdminCountryService $countryService
    ) {}

    public function index(Request $request)
    {
        $data = $this->countryService->getCountries($request);

        return view(
            'adminpage.contents.locations.countries.index',
            $data
        );
    }

    public function edit(Country $country)
    {
        return view(
            'adminpage.contents.locations.countries.edit',
            compact('country')
        );
    }

    public function store(Request $request)
    {
        $this->countryService->createCountry($request);

        return redirect()
            ->route('admin.locations.countries.index')
            ->with('success', 'Country created.');
    }

    public function update(Request $request, Country $country)
    {
        $this->countryService->updateCountry($request, $country);

        return redirect()
            ->route('admin.locations.countries.index')
            ->with('success', 'Country updated.');
    }

    public function destroy(Country $country)
    {
        $this->countryService->deleteCountry($country);

        return back()->with('success', 'Country deleted.');
    }

    public function updateMeta(Request $request, Country $country)
    {
        $this->countryService->updateMeta($request, $country);

        return back()->with('success', 'Country updated.');
    }
}