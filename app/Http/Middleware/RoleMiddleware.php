<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();

            if (!in_array($user->role, $roles, true)) {
                abort(403);
            }

            return $next($request);
        }

        if (Auth::check()) {
            $user = Auth::user();

            if (!in_array($user->role, $roles, true)) {
                abort(403);
            }

            return $next($request);
        }

        return redirect()->route('home');
    }
}
