<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CRUDController ;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('crud', CRUDController::class);

    Route::get('browse', [CarController::class, 'index'])->name('browse.cars');

    Route::get('/admin/dashboard', function () {
    return view('dashboard.admin');
    });

    Route::get('/staff/dashboard', function () {
        return view('dashboard.staff');
    });

    Route::get('/customer/dashboard', function () {
        return view('dashboard.customer');
    });


});

//Payment routes
Route::get('/payment', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{bookingID}', [PaymentController::class, 'submit'])->name('payment.submit');

require __DIR__.'/auth.php';
