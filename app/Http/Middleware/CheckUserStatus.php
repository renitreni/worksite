<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {

            $user = Auth::user();

            if ($user->account_status === 'disabled') {

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('home')
                    ->withErrors([
                        'email' => 'Your account has been disabled by the administrator.'
                    ]);
            }

            if ($user->account_status === 'hold') {

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('home')
                    ->withErrors([
                        'email' => 'Your account is currently on hold.'
                    ]);
            }
        }

        return $next($request);
    }
}