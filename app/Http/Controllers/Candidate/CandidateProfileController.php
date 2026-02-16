<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CandidateProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // ensure profile exists
        $profile = $user->candidateProfile()->firstOrCreate([]);

        return view('candidate.contents.profile', [
            'user' => $user,
            'profile' => $profile,
            'qualificationOptions' => $this->qualificationOptions(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $profile = $user->candidateProfile()->firstOrCreate([]);

        $validated = $request->validate([
            // user fields
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['required', 'string', 'max:80'],
            'phone'      => ['nullable', 'string', 'max:30'],

            // profile fields
            'address'           => ['nullable', 'string', 'max:255'],
            'birth_date'        => ['nullable', 'date'],
            'bio'               => ['nullable', 'string', 'max:2000'],
            'experience_years'  => ['nullable', 'integer', 'min:0', 'max:80'],

            // socials
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'telegram' => ['nullable', 'string', 'max:255'],

            // professional
            'highest_qualification' => ['nullable', 'string', 'max:80'],
            'current_salary'        => ['nullable', 'string', 'max:80'],

            // photo
            'photo' => ['nullable', 'image', 'max:5120'], // 5MB
        ]);

        // handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('candidate-photos', 'public');
            $profile->photo_path = $path;
        }


        $user->first_name = $validated['first_name'];
        $user->last_name  = $validated['last_name'];
        $user->name       = trim($validated['first_name'].' '.$validated['last_name']);
        $user->phone      = $validated['phone'] ?? null;
        $user->save();

        $profile->fill([
            'address'              => $validated['address'] ?? null,
            'birth_date'           => $validated['birth_date'] ?? null,
            'bio'                  => $validated['bio'] ?? null,
            'experience_years'     => $validated['experience_years'] ?? null,
            'whatsapp'             => $validated['whatsapp'] ?? null,
            'facebook'             => $validated['facebook'] ?? null,
            'linkedin'             => $validated['linkedin'] ?? null,
            'telegram'             => $validated['telegram'] ?? null,
            'highest_qualification' => $validated['highest_qualification'] ?? null,
            'current_salary'        => $validated['current_salary'] ?? null,
        ])->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password changed successfully.');
    }

    private function qualificationOptions(): array
    {
        return [
            'High School',
            'Senior High School',
            'Vocational / TESDA',
            'Associate Degree',
            "Bachelor's Degree",
            "Master's Degree",
            'Doctorate',
            'Other',
        ];
    }
}
