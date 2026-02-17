<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployerProfile;

class EmployerProfileController extends Controller
{
    /**
     * Show the company profile edit form.
     */
    public function editProfile()
    {

        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Load existing employer profile or create one
        $employerProfile = $user->employerProfile;
        if (!$employerProfile) {
            $employerProfile = $user->$employerProfile()->create([
                'company_name'       => '',
                'company_email'      => $user->email,
                'company_contact'    => '',
                'company_address'    => '',
                'representative_name' => $user->name,
                'position'           => '',
                'status'             => 'pending',
                'is_verified'        => 0,
            ]);
        }

        // Debug check
        // dd($employerProfile);

        return view('employer.contents.profile', compact('employerProfile'));
    }

    /**
     * Update the company profile.
     */
    public function updateProfile(Request $request)
    {
        $employerProfile = Auth::user()->employerProfile;

        if (!$employerProfile) {
            return redirect()->back()->with('error', 'Employer profile not found.');
        }

        // Validate only the existing DB fields
        $validated = $request->validate([
            'company_name'    => 'required|string|max:255',
            'company_email'   => 'required|email|max:255',
            'company_contact' => 'nullable|string|max:50',
            'company_address' => 'nullable|string|max:255',
        ]);

        $employerProfile->update($validated);

        return redirect()->route('employer.company-profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Delete employer account.
     */
    public function deleteAccount()
    {
        /** @var \App\Models\User $employer */

        $employer = Auth::user();

        // delete related records if needed
        // $employer->jobs()->delete();

        $employer->delete();   // delete database record
        Auth::logout();        // then logout

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
