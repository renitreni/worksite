<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\AgencyFollow;
use App\Models\EmployerProfile;
use Illuminate\Support\Facades\Auth;

class AgencyFollowController extends Controller
{
    public function toggle(EmployerProfile $employerProfile)
    {
        $userId = Auth::id();

        $follow = AgencyFollow::where('user_id', $userId)
            ->where('employer_profile_id', $employerProfile->id)
            ->first();

        if ($follow) {
            $follow->delete();

            return back()->with('success', 'Unfollowed agency');
        }

        AgencyFollow::create([
            'user_id' => $userId,
            'employer_profile_id' => $employerProfile->id
        ]);

        return back()->with('success', 'Agency followed');
    }
}