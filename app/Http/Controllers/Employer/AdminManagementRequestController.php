<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Employer\AdminManagementRequestService;

class AdminManagementRequestController extends Controller
{
    protected $service;

    public function __construct(AdminManagementRequestService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $profile = auth()->user()->employerProfile;

        $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        $this->service->createRequest($profile, $request->message);

        return back()->with('success', 'Request sent successfully.');
    }
}