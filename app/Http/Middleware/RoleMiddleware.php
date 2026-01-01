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

        // Kalau userType simpan integer
        $map = [
            'Admin' => 1,
            'Staff' => 2,
            'Customer' => 3,
        ];

        if (isset($map[$role]) && $userType == $map[$role]) {
            return $next($request);
        }

        return abort(403, 'Unauthorized');
    }
}


/*
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

*/