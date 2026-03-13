<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Candidate\CandidateProfileService;

class CandidateProfileController extends Controller
{
    protected $profileService;

    public function __construct(CandidateProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show(Request $request)
    {
        return $this->profileService->showProfile($request);
    }

    public function update(Request $request)
    {
        return $this->profileService->updateProfile($request);
    }

    public function updatePassword(Request $request)
    {
        return $this->profileService->updatePassword($request);
    }
}