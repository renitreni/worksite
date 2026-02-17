<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        $u = Auth::guard('admin')->user();

        if (!$u || $u->role !== 'superadmin') {
            abort(403);
        }

        return $next($request);
    }
}
