<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EmployerProfileController extends Controller
{
    /**
     * Show the company profile edit form.
     */
    public function editProfile()
    {
        $user = Auth::user();
        if (!$user) abort(403, 'Unauthorized');

        $employerProfile = $user->employerProfile;

        if (!$employerProfile) {
            $employerProfile = $user->employerProfile()->create([
                'company_name'         => '',
                'company_contact'      => '',
                'company_address'      => '',
                'representative_name'  => $user->name,
                'position'             => '',
                'status'               => 'pending',
            ]);
        }

        // NOTE: email comes from users table now
        $email = $user->email;

        return view('employer.contents.profile', compact('employerProfile', 'email'));
    }

    /**
     * Update the company profile.
     */

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $employerProfile = $user->employerProfile;

        if (!$employerProfile) {
            return back()->with('error', 'Employer profile not found.');
        }

        $validated = $request->validate([
            'company_name'    => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'company_contact' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'representative_name' => ['nullable', 'string', 'max:255'],
            'position'        => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($user, $employerProfile, $validated) {
            // update employer_profiles (NO email here)
            $employerProfile->update([
                'company_name'        => $validated['company_name'],
                'company_contact'     => $validated['company_contact'] ?? '',
                'company_address'     => $validated['company_address'] ?? '',
                'representative_name' => $validated['representative_name'] ?? $employerProfile->representative_name,
                'position'            => $validated['position'] ?? $employerProfile->position,
            ]);

            // update users.email
            $user->update([
                'email' => $validated['email'],
            ]);
        });

        return redirect()->route('employer.company-profile')
            ->with('success', 'Profile updated successfully.');
    }


    /**
     * Delete employer account.
     */
    public function deleteAccount()
    {
        $employer = Auth::user();

        $employer->delete();
        Auth::logout();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
