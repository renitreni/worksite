<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmployerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class EmployerAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register-employer');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'email', 'max:255', 'unique:employer_profiles,company_email'],

            'company_address' => ['required', 'string', 'max:255'],
            'company_contact' => ['required', 'string', 'max:50'],
            'company_contact_e164' => ['nullable', 'string', 'max:30'],

            'representative_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],

            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $repName = trim($validated['representative_name']);
        $parts = preg_split('/\s+/', $repName);
        $first = $parts[0] ?? $repName;
        $last = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';

        $phoneToSave = $validated['company_contact_e164'] ?: $validated['company_contact'];

        // ✅ Create user (NO is_verified here)
        $user = User::create([
            'first_name' => $first,
            'last_name'  => $last,
            'name'       => $repName,
            'email'      => $validated['company_email'],
            'phone'      => $phoneToSave,
            'role'       => 'employer',
            'password'   => bcrypt($validated['password']),
        ]);

        // ✅ Employer approval flow is here (status)
        EmployerProfile::create([
            'user_id' => $user->id,
            'company_name' => $validated['company_name'],
            'company_email' => $validated['company_email'],
            'company_address' => $validated['company_address'],
            'company_contact' => $phoneToSave,
            'representative_name' => $validated['representative_name'],
            'position' => $validated['position'],
            'status' => 'pending', // ✅ pending by default
        ]);

        return redirect()->route('employer.register')->with('showApprovalModal', true);
    }

    public function showLogin()
    {
        return view('auth.login-employer');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])
            ->where('role', 'employer')
            ->first();

        if (!$user || !password_verify($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Invalid employer email or password.'])
                ->onlyInput('email');
        }

        // ✅ Must have profile
        $profile = $user->employerProfile;

        if (!$profile) {
            return back()
                ->withErrors(['email' => 'Employer profile not found. Please contact support.'])
                ->onlyInput('email');
        }

        // ✅ Approval check using status
        if ($profile->status !== 'approved') {
            $msg = match ($profile->status) {
                'pending' => 'Your employer account is still pending admin approval. Please wait.',
                'rejected' => 'Your employer account was rejected. Please contact admin/support.',
                default => 'Your employer account is not approved yet. Please wait for admin approval.',
            };

            return back()
                ->withErrors(['email' => $msg])
                ->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('employer.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
