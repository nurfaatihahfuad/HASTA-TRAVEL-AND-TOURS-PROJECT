<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CRUDController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\RoleMiddleware;

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

Route::get('/registration/success', [CustomerRegistrationController::class, 'success'])
    ->name('registration.success')
    ->middleware('auth');

Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', RoleMiddleware::class.':Admin'])
    ->name('admin.dashboard');

Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
    ->middleware(['auth', RoleMiddleware::class.':Staff'])
    ->name('staff.dashboard');

Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
    ->middleware(['auth', RoleMiddleware::class.':Customer'])
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

// Payment routes
Route::get('/payment', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{bookingID}', [PaymentController::class, 'submit'])->name('payment.submit');

// Staff dashboard: list all payments
Route::get('/verify', [verifypaymentController::class, 'index'])->name('payment.index');
// Staff action: approve or reject a specific payment
Route::post('/verify/{paymentID}', [verifypaymentController::class, 'verify'])->name('payment.verify');


require __DIR__.'/auth.php';

require __DIR__.'/auth.php';
