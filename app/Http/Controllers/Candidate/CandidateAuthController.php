<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CandidateProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class CandidateAuthController extends Controller
{

    public function showRegister()
    {
        return view('auth.register-candidate');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],

            'contact_number' => ['required', 'string', 'max:30'],
            'contact_e164'   => ['nullable', 'string', 'max:40'],

            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'name'       => $fullName,
            'email'      => $validated['email'],
            'phone'      => $validated['contact_e164'] ?: $validated['contact_number'],
            'role'       => 'candidate',
            'password'   => $validated['password'], // hashed by cast
        ]);

        CandidateProfile::create([
            'user_id'         => $user->id,
            'contact_number'  => $validated['contact_number'],
            'contact_e164'    => $validated['contact_e164'] ?: null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('candidate.dashboard');
    }

    public function showLogin()
    {
        return view('auth.login-candidate');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['role'] = 'candidate';

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Invalid candidate email or password.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('candidate.dashboard');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
