<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userType = Auth::user()->userType;

        if (
            ($role === 'Admin' && $userType == 1) ||
            ($role === 'Staff' && $userType == 2) ||
            ($role === 'Customer' && $userType == 3)
        ) {
            return $next($request);
        }

        return redirect('/login');
    }
}