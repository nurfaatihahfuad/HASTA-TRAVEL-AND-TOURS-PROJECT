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

    ->middleware('auth')
    ->name('admin.dashboard');

Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
    ->middleware('auth')
    ->name('staff.dashboard');

Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
    ->middleware('auth')

    ->name('customer.dashboard');

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
    Route::get('/browseVehicle', [VehicleController::class, 'index'])->name('browse.cars');
    Route::get('/book-car/{vehicleID}', [BookingController::class, 'create'])->name('booking.form');
    Route::post('/book-car',  [BookingController::class, 'store'])->name('booking.store');

    // Payment routes
    Route::get('/payment/{bookingID}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{bookingID}', [PaymentController::class, 'submit'])->name('payment.submit');


    // Staff dashboard: list all payments
    Route::get('/verify', [verifypaymentController::class, 'index'])->name('payment.index');
    // Staff action: approve or reject a specific payment
    Route::post('/verify/{paymentID}', [verifypaymentController::class, 'verify'])->name('payment.verify');

    //require __DIR__.'/auth.php';





