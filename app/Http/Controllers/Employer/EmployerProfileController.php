<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Employer\EmployerProfileService;

class EmployerProfileController extends Controller
{
    public function __construct(
        private EmployerProfileService $profileService
    ) {}

    public function show()
    {
        $data = $this->profileService->getProfilePage();

        return view('employer.contents.profile.show', $data);
    }

    public function edit()
    {
        $data = $this->profileService->getEditPage();

        return view('employer.contents.profile.edit', $data);
    }

    public function update(Request $request)
    {
        $this->profileService->updateProfile($request);

        return redirect()
            ->route('employer.company-profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function deleteAccount()
    {
        $this->profileService->deleteAccount();

        return redirect('/')
            ->with('success', 'Your account has been deleted.');
    }
}