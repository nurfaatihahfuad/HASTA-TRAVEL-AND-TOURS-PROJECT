<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CRUDController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VehicleController;

// Welcome page guna VehicleController@preview 
Route::get('/', [VehicleController::class, 'preview'])->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Browse-car boleh diakses tanpa login
Route::get('/browseVehicle', [VehicleController::class, 'index'])->name('browse.vehicle');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('crud', CRUDController::class);

    Route::get('browse', [CarController::class, 'index'])->name('browse.cars');

});

// Payment routes
Route::get('/payment', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{bookingID}', [PaymentController::class, 'submit'])->name('payment.submit');

require __DIR__.'/auth.php';
