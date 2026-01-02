<?php

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

    public function login(LoginRequest $request): RedirectResponse
    {
        // Authenticate credentials
        $request->authenticate();
        $request->session()->regenerate();

        $role = auth()->user()->userType; // field userType dalam DB

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'staff') {
            return redirect()->route('staff.dashboard');
        } elseif ($role === 'customer') {
            return redirect()->route('customer.dashboard');
        }

        // fallback kalau role tak dikenali
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