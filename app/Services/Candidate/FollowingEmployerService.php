<?php

namespace App\Services\Candidate;

use Illuminate\Support\Facades\Auth;
use App\Models\AgencyFollow;

class FollowingEmployerService
{
    public function getFollowedEmployers()
    {
        $user = Auth::user();

        $agencies = AgencyFollow::with([
                'employerProfile.user',
                'employerProfile.jobPosts'
            ])
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($follow) {

                $agency = $follow->employerProfile;

                return [
                    'id' => $agency->id,
                    'name' => $agency->company_name,
                    'location' => $agency->company_address,
                    'description' => $agency->description,
                    'open_jobs' => $agency->jobPosts()
                        ->where('status','open')
                        ->count(),
                    'logo' => $agency->logo_path
                        ? asset('storage/'.$agency->logo_path)
                        : null,
                ];
            });

        return view(
            'candidate.contents.following-employers',
            compact('agencies')
        );
    }
}