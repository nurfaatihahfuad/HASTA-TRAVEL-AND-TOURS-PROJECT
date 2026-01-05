<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        Log::info('RoleMiddleware triggered', [
            'path' => $request->path(),
            'required_role' => $role,
            'is_auth' => Auth::check(),
            'user_id' => Auth::id(),
            'user_type' => Auth::check() ? Auth::user()->userType : 'none'
        ]);

        if (!Auth::check()) {
            Log::warning('User not authenticated in RoleMiddleware');
            return redirect('/login');
        }

        $user = Auth::user();
        $userType = $user->userType;

        // --- Logik Baru: Check specific roles guna Model Helper ---
        $hasAccess = false;

        if ($role === 'it' && $user->isITadmin()) {
            $hasAccess = true;
        } elseif ($role === 'finance' && $user->isFinanceAdmin()) {
            $hasAccess = true;
        } elseif ($role === 'salesperson' && $user->isSalesperson()) {
            $hasAccess = true;
        } elseif ($role === 'runner' && $user->isRunner()) {
            $hasAccess = true;
        } elseif ($role === 'customer' && $user->isCustomer()) {
            $hasAccess = true;
        } elseif ($userType === $role) {
            // Fallback jika role yang dihantar adalah basic (cth: 'admin')
            $hasAccess = true;
        }

        if ($hasAccess) {
            Log::info('Role check passed');
            return $next($request);
        }
        // --- End Logik Baru ---

        Log::warning('Role check failed', [
            'required' => $role,
            'actual' => $userType
        ]);

        /*if (Auth::user()->userType !== $role) {
            abort(403, 'Unauthorized');
        }*/

        //return $next($request);
    }
}