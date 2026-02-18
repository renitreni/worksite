<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next, string $guard = 'web')
    {
        $user = Auth::guard($guard)->user();

        if ($user) {
            // block archived
            if (!is_null($user->archived_at)) {
                Auth::guard($guard)->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Account is archived.']);
            }

            // block non-active
            if (($user->account_status ?? 'active') !== 'active') {
                Auth::guard($guard)->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')
                    ->withErrors(['email' => 'Account is not active.']);
            }
        }

        return $next($request);
    }
}
