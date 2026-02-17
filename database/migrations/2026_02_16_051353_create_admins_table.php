<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && (int) $admin->is_active === 0) {
            return back()
                ->withErrors(['email' => 'Your account is disabled.'])
                ->onlyInput('email');
        }

        if (!Auth::guard('admin')->attempt($request->only('email','password'), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Invalid credentials.'])
                ->onlyInput('email');
        }

        // update last login time (nice and matches your schema)
        $admin->forceFill(['last_login_at' => now()])->save();

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Logged out successfully.');
    }
}
