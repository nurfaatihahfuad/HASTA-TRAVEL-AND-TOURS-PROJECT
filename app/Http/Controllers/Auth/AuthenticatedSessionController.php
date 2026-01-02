<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        // If already logged in, redirect to appropriate dashboard
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }
        
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        Log::info('Login attempt', ['email' => $request->email]);
        
        // Authenticate using the email field
        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->boolean('remember'))) {
            Log::warning('Login failed', ['email' => $request->email]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
        
        Log::info('Login successful', [
            'userID' => Auth::id(),
            'email' => Auth::user()->email,
            'userType' => Auth::user()->userType
        ]);
        
        // Regenerate session
        $request->session()->regenerate();
        
        // Update session table with user ID
        DB::table('sessions')
            ->where('id', session()->getId())
            ->update(['user_id' => Auth::id()]);
        
        Log::info('Session updated in database', [
            'session_id' => session()->getId(),
            'user_id_set' => Auth::id()
        ]);
        
        return $this->redirectToDashboard();
    }

    private function redirectToDashboard()
    {
        $userType = Auth::user()->userType;
        
        if ($userType === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($userType === 'staff') {
            return redirect()->route('staff.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Remove session from database
        DB::table('sessions')
            ->where('id', session()->getId())
            ->delete();
            
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}