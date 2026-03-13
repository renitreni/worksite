<?php

namespace App\Services\Employer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Industry;

class EmployerProfileService
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
            ]);
        }

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

    public function getProfilePage(): array
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $profile = $this->ensureProfile($user);
        $profile->load('industries');

        return [
            'employerProfile' => $profile,
            'email' => $user->email
        ];
    }

    public function getEditPage(): array
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $profile = $this->ensureProfile($user);
        $profile->load('industries');

        $industries = Industry::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return [
            'employerProfile' => $profile,
            'email' => $user->email,
            'industries' => $industries
        ];
    }

    public function updateProfile($request)
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $profile = $this->ensureProfile($user);

        $validated = $request->validate([
            'company_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,' . $user->id],
            'company_contact' => ['nullable','string','max:50'],
            'company_address' => ['nullable','string','max:255'],
            'company_website' => ['nullable','string','max:255'],
            'dmw_license_number' => ['nullable','string','max:100'],
            'description' => ['nullable','string','max:2000'],

            'industries' => ['nullable','array','max:20'],
            'industries.*' => ['integer','exists:industries,id'],

            'logo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'cover' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        DB::transaction(function () use ($user,$profile,$validated,$request) {

            $logoPath = $profile->logo_path;
            $coverPath = $profile->cover_path;

            if ($request->hasFile('logo')) {

                if ($logoPath) {
                    Storage::disk('public')->delete($logoPath);
                }

                $logoPath = $request
                    ->file('logo')
                    ->store('employers/logos','public');
            }

            if ($request->hasFile('cover')) {

                if ($coverPath) {
                    Storage::disk('public')->delete($coverPath);
                }

                $coverPath = $request
                    ->file('cover')
                    ->store('employers/covers','public');
            }

            $profile->update([
                'company_name' => $validated['company_name'],
                'company_contact' => $validated['company_contact'] ?? '',
                'dmw_license_number' => $validated['dmw_license_number'] ?? null,
                'company_address' => $validated['company_address'] ?? '',
                'company_website' => $validated['company_website'] ?? '',
                'description' => $validated['description'] ?? '',
                'logo_path' => $logoPath,
                'cover_path' => $coverPath,
            ]);

            $profile->industries()->sync($validated['industries'] ?? []);

            $user->update([
                'email' => $validated['email']
            ]);
        });
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        abort_if(!$user, 403);

        $user->delete();

        Auth::logout();
    }
}