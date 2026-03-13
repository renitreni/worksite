<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;
use App\Services\Admin\AdminIndustryService;

class IndustryController extends Controller
{
    public function __construct(
        private AdminIndustryService $industryService
    ) {}

    public function index(Request $request)
    {
        $data = $this->industryService->getIndustries($request);

        return view(
            'adminpage.contents.industries.index',
            $data
        );
    }

    public function edit(Industry $industry)
    {
        return view(
            'adminpage.contents.industries.edit',
            compact('industry')
        );
    }

    public function store(Request $request)
    {
        $this->industryService->createIndustry($request);

        return redirect()
            ->route('admin.industries.index')
            ->with('success', 'Industry created.');
    }

    public function update(Request $request, Industry $industry)
    {
        $this->industryService->updateIndustry($request, $industry);

        return redirect()
            ->route('admin.industries.index')
            ->with('success', 'Industry updated.');
    }

    public function destroy(Industry $industry)
    {
        $this->industryService->deleteIndustry($industry);

        return back()->with('success', 'Industry deleted.');
    }

    public function updateMeta(Request $request, Industry $industry)
    {
        $this->industryService->updateMeta($request, $industry);

        return back()->with('success', 'Industry updated.');
    }
}