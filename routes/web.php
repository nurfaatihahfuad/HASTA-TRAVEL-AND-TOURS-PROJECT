<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CRUDController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\RoleMiddleware;

// Welcome page guna VehicleController@preview
Route::get('/', [VehicleController::class, 'preview'])->name('welcome');
Route::get('/search', [VehicleController::class, 'search'])->name('vehicles.search');

// Browse-car boleh diakses tanpa login
Route::get('/browseVehicle', [VehicleController::class, 'index'])->name('browse.vehicle');

// Auth routes
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Dashboard routes ikut role (guna middleware class terus)
Route::get('/admin/dashboard', function () {
    return view('dashboard.admin');
})->middleware(['auth', RoleMiddleware::class.':Admin'])->name('admin.dashboard');

Route::get('/staff/dashboard', function () {
    return view('dashboard.staff');
})->middleware(['auth', RoleMiddleware::class.':Staff'])->name('staff.dashboard');

Route::get('/customer/dashboard', function () {
    return view('dashboard.customer');
})->middleware(['auth', RoleMiddleware::class.':Customer'])->name('customer.dashboard');

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

require __DIR__.'/auth.php';