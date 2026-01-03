<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CRUDController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\BookingController;

use App\Http\Controllers\verifypaymentController;

use App\Http\Controllers\CustomerRegistrationController;
use App\Http\Controllers\StaffController;


// Welcome page guna VehicleController@preview
Route::get('/', [VehicleController::class, 'preview'])->name('welcome');
Route::get('/search', [VehicleController::class, 'search'])->name('vehicles.search');

// Browse-car boleh diakses tanpa login
Route::get('/browseVehicle', [VehicleController::class, 'index'])->name('browse.vehicle');

// Auth routes
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Customer Registration Routes
Route::get('/register/customer', [CustomerRegistrationController::class, 'create'])
    ->name('customer.register')
    ->middleware('guest');

Route::post('/register/customer', [CustomerRegistrationController::class, 'store'])
    ->name('customer.register.store');

// Successful Registration
Route::get('/register/customer/success', [CustomerRegistrationController::class, 'success'])
    ->name('customer.register.success'); //here

//login
Route::get('/login', [AuthenticatedSessionController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'login']);

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

//login route to dashboard    
Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', RoleMiddleware::class.':admin'])
    ->name('admin.dashboard');

Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
    ->middleware(['auth', RoleMiddleware::class.':staff'])
    ->name('staff.dashboard');
/*
Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
    ->middleware(['auth', RoleMiddleware::class.':customer'])
    ->middleware('auth')
    ->name('admin.dashboard');

Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
    ->middleware('auth')
    ->name('staff.dashboard');

Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
    ->middleware('auth')
    ->name('customer.dashboard');*/

// Protected routes (auth required)
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD
    Route::resource('crud', CRUDController::class);

    // Browse cars
    Route::get('browse', [CarController::class, 'index'])->name('browse.cars');
});



// Booking form without vehicle (optional)
Route::get('/book-car', [BookingController::class, 'create'])->name('booking.form');

// Booking form with vehicle (requires login)
Route::get('/book-car/{vehicle_id}', [BookingController::class, 'create'])
    ->middleware('auth')
    ->name('booking.withVehicle');

// Store booking
Route::post('/book-car', [BookingController::class, 'store'])->name('booking.store');

// Browse page (public)
Route::get('/browseVehicle', [VehicleController::class, 'index'])->name('browse.cars');


// Payment routes
Route::get('/payment', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{bookingID}', [PaymentController::class, 'submit'])->name('payment.submit');
// Staff dashboard: list all payments
Route::get('/verify', [verifypaymentController::class, 'index'])->name('payment.index');
// Staff action: approve or reject a specific payment
Route::post('/verify/{paymentID}', [verifypaymentController::class, 'verify'])->name('payment.verify');

// Staff Management Routes (Admin only)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('staff.')->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('create');
    Route::post('/staff', [StaffController::class, 'store'])->name('store');
    Route::get('/staff/{id}', [StaffController::class, 'show'])->name('show');
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('destroy');
});


//require __DIR__.'/auth.php';
