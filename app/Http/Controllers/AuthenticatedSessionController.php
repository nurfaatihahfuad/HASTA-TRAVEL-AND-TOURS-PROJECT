<?php

/* ----------------------------------------dina
namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class AuthenticatedSessionController extends Controller
{
    public function showLogin(): View
    {
        // Sentiasa tunjuk login form
        return view('auth.login');
    } 

    public function login(LoginRequest $request): RedirectResponse
    {
        // Authenticate credentials
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();
        $role = auth()->user()->userType; // field userType dalam DB

        if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
        } elseif ($role === 'staff') {
            return redirect()->route('staff.dashboard');
        } elseif ($role === 'customer') {
            return redirect()->route('customer.dashboard');
        }*/
        
/*-----------------------------------------------------------------dina

        }

    /*if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'staff_salesperson') {
        return redirect()->route('staff_salesperson.dashboard');
    } elseif ($role === 'staff_runner') {
        return redirect()->route('staff_runner.dashboard');
    } elseif ($role === 'customer') {
        return redirect()->route('customer.dashboard');
    }
      
>>>>>>> Stashed changes
        $user = auth()->user();
        if($user->isITadmin()) {
            return redirect()->route('admin.dashboard'); // nnti tukar pegi IT admin dashboard
        } else if($user->isFinanceAdmin()) {
            return redirect()->route('admin.dashboard'); // nnti tukar pegi finance admin dashboard
        } else if($user->isRunner()) {
            return redirect()->route('staff_runner.dashboard');
        } else if($user->isSalesperson()) {
            return redirect()->route('staff_salesperson.dashboard');
        } else if($user->customer()) {
            return redirect()->route('customer.dashboard');
        }
            */
        // Block if email not verified (Laravel built-in)
        /*if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Please verify your email before logging in.'
            ]);
        }*/

        // Role specific
        /*if ($user->userType === 'customer') {
            if ($user->customer->customerStatus !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is not active yet. Please wait for staff verification and activation.'
                ]);
            }
            return redirect()->route('customer.dashboard');
        }

        if ($user->userType === 'staff') {
            if (!$user->staff->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your staff account is not active yet. Please check your activation email.'
                ]);
            }
            return redirect()->route('staff.dashboard');
        }

        if ($user->userType === 'admin') {
            if (!$user->admin->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your admin account is not active yet. Please check your activation email.'
                ]);
            }
            return redirect()->route('admin.dashboard');
        }*/

        // fallback kalau role tak dikenali

    /*----------------------------------dina
        return redirect('/');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

*/

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function showLogin(): View
    {
        // Sentiasa tunjuk login form
        return view('auth.login');
    } 

    /*public function login(LoginRequest $request): RedirectResponse
    {
        // Authenticate credentials
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();

        if ($user->isITadmin()) {
            //return redirect()->route('admin.it.dashboard');
            return redirect()->route('admin.dashboard');
        } elseif ($user->isFinanceAdmin()) {
            //return redirect()->route('admin.finance.dashboard');
            return redirect()->route('admin.dashboard');
        } elseif ($user->isRunner()) {
            return redirect()->route('staff.runner.dashboard');
        } elseif ($user->isSalesperson()) {
            return redirect()->route('staff.salesperson.dashboard');
        } elseif ($user->isCustomer()) {
            return redirect()->route('customer.dashboard');
        }

        // fallback kalau role tak dikenali
        return redirect('/');
    }*/
        public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();
        
        \Log::info('User logged in', [
    'userID' => $user->userID,
    'type' => $user->userType,
]);

\Log::info('User logged in', ['userID' => $user->userID, 'type' => $user->userType]);

        
        // Redirect based on user type and role
        if ($user->isCustomer()) {
            return redirect()->route('customer.dashboard');
        }
        
    
        
        if ($user->isSalesperson()) {
            return redirect()->route('staff_salesperson.dashboard');
        }
        
        if ($user->isRunner()) {
            return redirect()->route('staff_salesperson.dashboard');
        }

        if ($user->isITadmin()) {
            return redirect()->route('admin_it.dashboard'); // or admin.it.dashboard if you have it
        }
        
        if ($user->isFinanceAdmin()) {
            return redirect()->route('admin_finance.dashboard'); // or admin.finance.dashboard
        }
        
        
        
        // Fallback
        \Log::warning('No specific role matched, redirecting to home');
        return redirect('/');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
