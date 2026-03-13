<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LocationSuggestion;
use Illuminate\Http\Request;
use App\Services\Admin\AdminLocationSuggestionService;

class LocationSuggestionController extends Controller
{
    public function __construct(
        private AdminLocationSuggestionService $service
    ) {}

    public function index(Request $request)
    {
        $data = $this->service->getSuggestions($request);

        return view(
            'adminpage.contents.location_suggestions.index',
            $data
        );
    }

    public function update(Request $request, LocationSuggestion $suggestion)
    {
        $this->service->updateStatus($request, $suggestion);

        return back()->with('success', 'Suggestion updated.');
    }

    public function destroy(LocationSuggestion $suggestion)
    {
        $this->service->deleteSuggestion($suggestion);

        return back()->with('success', 'Suggestion deleted.');
    }

    public function approve(LocationSuggestion $suggestion)
    {
        $this->service->approveSuggestion($suggestion);

        return back()->with(
            'success',
            'Suggestion approved and added to Locations.'
        );
    }
}