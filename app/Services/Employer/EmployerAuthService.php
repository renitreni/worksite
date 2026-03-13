<?php

namespace App\Services\Employer;

use App\Models\User;
use App\Models\EmployerProfile;
use App\Notifications\AdminUserRegistered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployerAuthService
{
    public function register($request)
    {
        $validated = $request->validated();

        $repName = trim($validated['representative_name']);
        $parts = preg_split('/\s+/', $repName);

        $first = $parts[0] ?? $repName;
        $last = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

        $phoneToSave = $validated['company_contact_e164'] ?: $validated['company_contact'];

        $user = User::create([
            'first_name' => $first,
            'last_name' => $last,
            'name' => $repName,
            'email' => $validated['email'],
            'phone' => $phoneToSave,
            'role' => 'employer',
            'password' => Hash::make($validated['password']),
            'account_status' => 'active',
        ]);

        $profile = EmployerProfile::create([
            'user_id' => $user->id,
            'company_name' => $validated['company_name'],
            'company_address' => $validated['company_address'],
            'company_contact' => $phoneToSave,
            'company_website' => null,
            'description' => null,
            'logo_path' => null,
            'cover_path' => null,
            'representative_name' => $validated['representative_name'],
            'position' => $validated['position'],
        ]);

        $profile->verification()->create([
            'status' => 'pending',
            'approved_at' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
            'suspended_reason' => null,
        ]);

        User::where('role', 'admin')
            ->orWhere('role', 'superadmin')
            ->get()
            ->each(function ($admin) use ($user) {
                $admin->notify(new AdminUserRegistered($user));
            });

        return redirect()
            ->route('employer.register')
            ->with('showApprovalModal', true);
    }

    public function login($request)
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])
            ->where('role', 'employer')
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Invalid employer email or password.'])
                ->onlyInput('email');
        }

        $accStatus = $user->account_status ?? 'active';

        if ($accStatus !== 'active') {

            $msg = $accStatus === 'hold'
                ? 'Your account is currently on hold. Please contact support.'
                : 'Your account has been disabled by the administrator.';

            return back()
                ->withErrors(['email' => $msg])
                ->onlyInput('email');
        }

        $profile = $user->employerProfile;

        if (!$profile) {
            return back()
                ->withErrors(['email' => 'Employer profile not found.'])
                ->onlyInput('email');
        }

        $status = $profile->verification?->status ?? 'pending';

        if ($status !== 'approved') {

            $msg = match ($status) {
                'pending' => 'Your employer account is still pending admin approval.',
                'rejected' => 'Your employer registration was rejected.',
                'suspended' => 'Your employer account is suspended.',
                default => 'Your employer account is not approved.',
            };

            return back()
                ->withErrors(['email' => $msg])
                ->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('employer.dashboard');
    }

    public function logout($request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}