<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; //dina tambah ni for blacklist
use Illuminate\View\View;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function showLogin(): View
    {
        // Sentiasa tunjuk login form
        return view('auth.login');
    } 

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

        // ✅ TAMBAH CHECK BLACKLIST DI SINI (SEBELUM REDIRECT) ---dina tambah
        // Check if user is a customer and blacklisted
        /*if ($user->userType === 'customer') {
            $customerStatus = DB::table('customer')
                ->where('userID', $user->userID)
                ->value('customerStatus');
            
            if ($customerStatus === 'blacklisted') {
                // Logout and block access
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')
                    ->with('error', 'Your account has been blacklisted. Please contact customer support.')
                    ->withInput($request->only('email'));
            }
        } //------sampai sini*/
         if ($user->isCustomer() && $user->is_blacklisted) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Your account has been blacklisted. Please contact customer support.'
            ]);
        }
        
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
