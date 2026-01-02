<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('login');
    }

    // Handle login submission
    public function login(Request $request)
    {
        // Trim all inputs (consistent with your registration)
        $request->merge(array_map('trim', $request->all()));

        // Validate request (simpler than registration)
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Attempt to authenticate
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Get the authenticated user
            $user = Auth::user();
            
            // Check if user is a customer (consistent with your registration)
            /*if ($user->userType !== 'customer') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'This account is not a customer account.',
                ]);
            }
            
            // Redirect to customer dashboard
            /*return redirect()->route('customer.dashboard')
                ->with('success', 'Login successful! Welcome back, ' . $user->name);*/
                // Instead of redirecting to success page, redirect to home with message
            /*return redirect('/')
                ->with('login_success', true)
                ->with('user_name', $user->name);*/
            // In login method, change redirect to:
            return redirect()->route('customer.dashboard')->with('success', 'Login successful!');

        }

        // If authentication fails
        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')
            ->with('success', 'You have been logged out successfully.');
    }
}