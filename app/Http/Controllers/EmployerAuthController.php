<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployerAuthController extends Controller
{
    // Show login form
    public function showLogin() {
        return view('auth.login'); // shared login Blade
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Only attempt login if role is 'employer'
        if (Auth::attempt(array_merge($credentials, ['role' => 'employer']))) {

            $request->session()->regenerate();

            return redirect()->route('employer.dashboard');
        }

        // If login fails (wrong credentials or not an employer)
        return back()->withErrors([
            'email' => 'Invalid credentials or you are not registered as an employer.',
        ])->withInput();
    }

    // Show registration form
    public function showRegister() {
        return view('auth.register'); // shared registration Blade
    }

    // Handle registration
    public function register(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
            'phone' => 'sometimes|string|max:20',
            'role' => 'required|in:candidate,employer', // form passes role dynamically
        ]);

        // Reject non-employers
        if ($data['role'] !== 'employer') {
            return redirect()->back()->withErrors([
                'role' => 'Only employer registration is allowed here.'
            ]);
        }

        // Split name
        $names = explode(' ', $data['name'], 2);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'employer',
            'first_name' => $names[0],
            'last_name' => $names[1] ?? '',
            'phone' => $data['phone'] ?? '',
        ]);

        Auth::login($user);

        return redirect()->route('employer.dashboard');
    }

    // Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('employer.login');
    }
}