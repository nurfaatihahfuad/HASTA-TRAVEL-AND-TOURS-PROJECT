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
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\verifypaymentController;
use App\Http\Controllers\CustomerRegistrationController;
use App\Http\Controllers\InspectionController;

// ============================
// Welcome & Vehicle browsing
// ============================
Route::get('/', [VehicleController::class, 'preview'])->name('welcome');
Route::get('/vehicles/search', [VehicleController::class, 'search'])->name('vehicles.search');
Route::get('/browseVehicle', [VehicleController::class, 'index'])->name('browse.vehicle');

// ============================
// Auth routes
// ============================
Route::get('/login', [AuthenticatedSessionController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ============================
// Customer Registration
// ============================
Route::get('/register/customer', [CustomerRegistrationController::class, 'create'])
    ->name('customer.register')
    ->middleware('guest');

Route::post('/register/customer', [CustomerRegistrationController::class, 'store'])
    ->name('customer.register.store');

Route::get('/register/customer/success', [CustomerRegistrationController::class, 'success'])
    ->name('customer.register.success');

// ============================
// Dashboard Routes (role-based)
// ============================

// Customer Dashboard
Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
    ->middleware(['auth', RoleMiddleware::class.':customer'])
    ->name('customer.dashboard');
    
Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', RoleMiddleware::class.':admin'])
    ->name('admin.dashboard');

// Admin IT Dashboard
Route::get('/admin/it/dashboard', [DashboardController::class, 'adminIT'])
    ->middleware(['auth', RoleMiddleware::class.':adminIT'])
    ->name('admin.it.dashboard');

// Admin Finance Dashboard
Route::get('/admin/finance/dashboard', [DashboardController::class, 'adminFinance'])
    ->middleware(['auth', RoleMiddleware::class.':adminFinance'])
    ->name('admin.finance.dashboard');

// Staff Salesperson Dashboard
Route::get('/staff/salesperson/dashboard', [DashboardController::class, 'staffSalesperson'])
    ->middleware(['auth', RoleMiddleware::class.':salesperson'])
    ->name('staff.salesperson.dashboard');

// Staff Runner Dashboard
Route::get('/staff/runner/dashboard', [DashboardController::class, 'staffRunner'])
    ->middleware(['auth', RoleMiddleware::class.':runner'])
    ->name('staff.runner.dashboard');

// ============================
// Booking routes (customer only)
// ============================
Route::middleware('auth')->group(function () {
    Route::get('/book-car/{vehicleID}', [BookingController::class, 'create'])->name('booking.form');
    Route::post('/book-car', [BookingController::class, 'store'])->name('booking.store');
});

// ============================
// Protected routes (auth required)
// ============================
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD
    Route::resource('crud', CRUDController::class);
});

// Browse cars ? ni diperlukan ke? kalau ya bole uncomment
    //Route::get('browse', [CarController::class, 'index'])->name('browse.cars');   

    // Car inspection to damage case
    Route::post('/inspection/store', [InspectionController::class, 'store'])->name('inspection.store');
    Route::post('/damage-case/resolve/{id}', [DamageCaseController::class, 'resolve'])->name('damage.resolve');

    // Inspection page
    Route::get('/inspection', [InspectionController::class, 'index'])->name('inspection.index');

    // Damage Case page
   Route::get('/damage-case', [DamageCaseController::class, 'index'])->name('damage.index');

// ============================
// Payment routes
// ============================
Route::get('/payment', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment', [PaymentController::class, 'submit'])->name('payment.submit');

// Staff dashboard: verify payments
Route::get('/verify', [verifypaymentController::class, 'index'])->name('payment.index');
Route::post('/verify/{paymentID}', [verifypaymentController::class, 'verify'])->name('payment.verify');

// ============================
// Staff Management (Admin only)
// ============================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('staff.')->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('create');
    Route::post('/staff', [StaffController::class, 'store'])->name('store');
    Route::get('/staff/{id}', [StaffController::class, 'show'])->name('show');
    Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('edit');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('update');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('destroy');
});

// ============================
// Admin Management (Admin only)
// ============================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admins.')->group(function () {
    Route::get('/admins', [AdminController::class, 'index'])->name('index');
    Route::get('/admins/create', [AdminController::class, 'create'])->name('create');
    Route::post('/admins', [AdminController::class, 'store'])->name('store');
    Route::get('/admins/{id}', [AdminController::class, 'show'])->name('show');
    Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('edit');
    Route::put('/admins/{id}', [AdminController::class, 'update'])->name('update');
    Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('destroy');
});
