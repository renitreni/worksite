<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin-auth.adminlogin');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Only allow admins
    $credentials['role'] = 'admin';

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Welcome back, Admin!');
    }

    return back()
        ->withErrors(['email' => 'Invalid admin credentials.'])
        ->withInput($request->only('email'));
}

    public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
}
}
