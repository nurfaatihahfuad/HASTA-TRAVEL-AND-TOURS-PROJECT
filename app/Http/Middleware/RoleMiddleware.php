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

        if ($role === 'adminIT' && $user->isITadmin()) {
            $hasAccess = true;
        } elseif ($role === 'adminFinance' && $user->isFinanceAdmin()) {
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
                // Check specific admin sub-roles for redirection
                if ($user->isITadmin()) {
                    return redirect()->route('admin.it.dashboard');
                } elseif ($user->isFinanceAdmin()) {
                    return redirect()->route('admin.finance.dashboard');
                }
                return redirect('/'); // Fallback if admin type not found
            
            case 'staff':
                if ($user->staff) {
                    // Redirect to appropriate staff dashboard
                    if ($user->staff->staffRole === 'salesperson') {
                        return redirect()->route('staff.salesperson.dashboard');
                    } elseif ($user->staff->staffRole === 'runner') {
                        return redirect()->route('staff.runner.dashboard');
                    }
                }
                return redirect('/');

            case 'customer':
                return redirect()->route('customer.dashboard');

            default:
                Auth::logout();
                return redirect('/login')->withErrors(['role' => 'Invalid user role']);
        }

        /*if (Auth::user()->userType !== $role) {
            abort(403, 'Unauthorized');
        }*/

        //return $next($request);
    }
}