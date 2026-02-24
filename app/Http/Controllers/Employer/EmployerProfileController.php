<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Industry;

class EmployerProfileController extends Controller
{
    private function ensureProfile($user)
    {
        $profile = $user->employerProfile;

        if (!$profile) {
            $profile = $user->employerProfile()->create([
                'company_name' => '',
                'company_contact' => '',
                'company_address' => '',
                'company_website' => '',
                'description' => '',
                'total_profile_views' => 0,
                'representative_name' => $user->name,
                'position' => '',
                // ✅ status removed (now in employer_verifications)
            ]);
        }

        // ✅ ensure verification row exists (pending by default)
        $profile->verification()->firstOrCreate(
            ['employer_profile_id' => $profile->id],
            [
                'status' => 'pending',
                'approved_at' => null,
                'rejected_at' => null,
                'rejection_reason' => null,
                'suspended_reason' => null,
            ]
        );

        return $profile;
    }

    public function show()
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $employerProfile = $this->ensureProfile($user);
        $employerProfile->load('industries');
        $email = $user->email;

        return view('employer.contents.profile.show', compact('employerProfile', 'email'));
    }

    public function edit()
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $employerProfile = $this->ensureProfile($user);
        $employerProfile->load('industries');
        $email = $user->email;

        $industries = Industry::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('employer.contents.profile.edit', compact('employerProfile', 'email', 'industries'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $employerProfile = $this->ensureProfile($user);

        $validated = $request->validate([
            'company_name'    => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'company_contact' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'company_website' => ['nullable', 'string', 'max:255'],
            'description'     => ['nullable', 'string', 'max:2000'],

            'industries'      => ['nullable', 'array', 'max:20'],
            'industries.*'    => ['integer', 'exists:industries,id'],

            'logo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cover'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        DB::transaction(function () use ($user, $employerProfile, $validated, $request) {

            $logoPath = $employerProfile->logo_path;
            $coverPath = $employerProfile->cover_path;

            if ($request->hasFile('logo')) {
                if ($logoPath) Storage::disk('public')->delete($logoPath);
                $logoPath = $request->file('logo')->store('employers/logos', 'public');
            }

            if ($request->hasFile('cover')) {
                if ($coverPath) Storage::disk('public')->delete($coverPath);
                $coverPath = $request->file('cover')->store('employers/covers', 'public');
            }

            $employerProfile->update([
                'company_name'    => $validated['company_name'],
                'company_contact' => $validated['company_contact'] ?? '',
                'company_address' => $validated['company_address'] ?? '',
                'company_website' => $validated['company_website'] ?? '',
                'description'     => $validated['description'] ?? '',
                'logo_path'       => $logoPath,
                'cover_path'      => $coverPath,
            ]);

            $employerProfile->industries()->sync($validated['industries'] ?? []);

            $user->update([
                'email' => $validated['email'],
            ]);
        });

        return redirect()->route('employer.company-profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $user->delete();
        Auth::logout();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
