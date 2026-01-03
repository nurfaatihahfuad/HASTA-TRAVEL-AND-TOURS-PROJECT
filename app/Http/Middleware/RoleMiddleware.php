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

        $userType = Auth::user()->userType;

        // Check if user has the required role
        if ($userType == $role) {
            Log::info('Role check passed');
            return $next($request);
        }

        Log::warning('Role check failed', [
            'required' => $role,
            'actual' => $userType
        ]);

        // Redirect to appropriate dashboard based on actual role
        /*if ($userType == 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($userType == 'staff') {
            return redirect('/staff/dashboard');
        } elseif ($userType == 'customer') {
            return redirect('/customer/dashboard');
        }*/

        switch ($userType) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'staff':
                if ($user->staff) {
                    // Redirect to appropriate staff dashboard
                    if ($user->staff->staffRole === 'salesperson') {
                        return redirect('/staff_salesperson/dashboard');
                    } elseif ($user->staff->staffRole === 'runner') {
                        return redirect('/staff_runner/dashboard');
                    }
                }
            case 'customer':
                return redirect('/customer/dashboard');

            default:
                Auth::logout();
                return redirect('/login')->withErrors(['role' => 'Invalid user role']);
        }

            return redirect()->route('login');
        }

        /*if (Auth::user()->userType !== $role) {
            abort(403, 'Unauthorized');
        }*/

        //return $next($request);

    }

