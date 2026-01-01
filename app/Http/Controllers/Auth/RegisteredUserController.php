<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'userID'   => Str::uuid(),
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'userType' => 3, // default Customer
            'noHP'     => $request->noHP ?? '',
            'noIC'     => $request->noIC ?? '',
        ]);

        event(new Registered($user));

        // Auto login selepas register
        Auth::login($user);

        // Redirect ikut role
        if ($user->userType == 1) { // Admin
            return redirect()->route('admin.dashboard');
        } elseif ($user->userType == 2) { // Staff
            return redirect()->route('staff.dashboard');
        } else { // Customer
            return redirect()->route('customer.dashboard');
        }
    }
}