<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Services\Admin\AdminAreaService;

class AreaController extends Controller
{
    public function __construct(
        private AdminAreaService $areaService
    ) {
    }

    public function index(Request $request, Country $country, City $city)
    {
        $data = $this->areaService->getAreas($request, $country, $city);

        return view(
            'adminpage.contents.locations.areas.index',
            $data
        );
    }

    public function edit(Country $country, City $city, Area $area)
    {
        $this->areaService->validateArea($country, $city, $area);

        return view(
            'adminpage.contents.locations.areas.edit',
            compact('country', 'city', 'area')
        );
    }

    public function store(Request $request, Country $country, City $city)
    {
        $this->areaService->createArea($request, $country, $city);

        return redirect()
            ->route('admin.locations.areas.index', [$country, $city])
            ->with('success', 'Area created.');
    }

    public function update(Request $request, Country $country, City $city, Area $area)
    {
        $this->areaService->updateArea($request, $country, $city, $area);

        return redirect()
            ->route('admin.locations.areas.index', [$country, $city])
            ->with('success', 'Area updated.');
    }

    public function destroy(Country $country, City $city, Area $area)
    {
        $this->areaService->deleteArea($country, $city, $area);

        return back()->with('success', 'Area deleted.');
    }

    public function updateMeta(Request $request, Country $country, City $city, Area $area)
    {
        $this->areaService->updateMeta($request, $country, $city, $area);

        return back()->with('success', 'Area updated.');
    }
}