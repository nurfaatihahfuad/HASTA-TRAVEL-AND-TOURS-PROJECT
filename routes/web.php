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
use App\Http\Controllers\CustomerRegistrationController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DamageCaseController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CustomerProfileController;


// ============================
// Welcome & Vehicle browsing
// ============================
Route::get('/', [VehicleController::class, 'preview'])->name('welcome');
Route::get('/vehicles/search', [VehicleController::class, 'search'])->name('vehicles.search');
Route::get('/browseVehicle', [VehicleController::class, 'index'])->name('browse.vehicle');
Route::get('/admin/vehicles', [VehicleController::class, 'indexAdmin'])->name('vehicles.index');

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

// Customer Dashboard ---ROUTE CHECKED
Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
    ->middleware(['auth', RoleMiddleware::class.':customer'])
    ->name('customer.dashboard');

// Customer Profile & Vouchers
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/customer/profile', [CustomerProfileController::class, 'show'])->name('customer.profile');
    Route::put('/customer/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
    
    // Voucher redemption - MUST HAVE voucherCode PARAMETER
    Route::post('/customer/voucher/{voucherCode}/redeem', [CustomerProfileController::class, 'redeem'])
        ->name('customer.voucher.redeem');
});

// In routes/web.php
Route::get('/test-raw-update/{voucherCode}', function($voucherCode) {
    $customer = Auth::user()->customer;
    
    \Log::info("🔧 === TEST RAW UPDATE ===");
    \Log::info("🔧 Customer: {$customer->userID}");
    \Log::info("🔧 Voucher: {$voucherCode}");
    
    // Method that worked in SQL test
    $result = DB::update("
        UPDATE customer_voucher 
        SET redeemed_at = NOW() 
        WHERE customerID = ? AND voucherCode = ?
    ", [$customer->userID, $voucherCode]);
    
    \Log::info("🔧 Raw SQL update: {$result} rows affected");
    
    // Verify
    $after = DB::table('customer_voucher')
        ->where('customerID', $customer->userID)
        ->where('voucherCode', $voucherCode)
        ->first();
    
    \Log::info("🔧 After - redeemed_at: " . ($after->redeemed_at ?? 'NULL'));
    
    return redirect()->route('customer.profile')
        ->with('info', "Test: {$result} rows updated, redeemed_at = " . ($after->redeemed_at ?? 'NULL'));
})->middleware('auth');
        
// Admin 
Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', RoleMiddleware::class.':admin'])
    ->name('admin.dashboard');

// Admin IT Dashboard
Route::get('/admin/it/dashboard', [DashboardController::class, 'adminIT'])
    ->middleware(['auth', RoleMiddleware::class.':admin'])
    ->name('admin_it.dashboard');

// Admin IT Manage Users 
/*Route::get('/admin/it/users', [AdminUserController::class, 'index']) 
    ->middleware(['auth', RoleMiddleware::class.':adminIT']) 
    ->name('admin.it.users');*/

// Admin Finance Dashboard
Route::get('/admin/finance/dashboard', [DashboardController::class, 'adminFinance'])
    ->middleware(['auth', RoleMiddleware::class.':adminFinance'])
    ->name('admin_finance.dashboard');

// Staff Runner Dashboard ---ROUTE CHECKED
Route::get('/staff/runner/dashboard', [DashboardController::class, 'staffRunner'])
    ->middleware(['auth', RoleMiddleware::class.':staff'])
    ->name('staff_runner.dashboard');

// Staff Salesperson Dashboard ---ROUTE CHECKED
Route::get('/staff/salesperson/dashboard', [DashboardController::class, 'staffSalesperson'])
    ->middleware(['auth', RoleMiddleware::class.':staff'])
    ->name('staff_salesperson.dashboard');

//Auni Letak yg ni tau
Route::put('/booking/{bookingID}/status', [BookingController::class, 'updateStatus']) 
    ->name('booking.updateStatus');

// Nak approve payment
Route::post('/booking/{bookingID}/approve', [BookingController::class, 'approve'])->name('booking.approve');
Route::post('/booking/{bookingID}/reject', [BookingController::class, 'reject'])->name('booking.reject');


// Route untuk tunjuk summary payment/booking 
Route::get('/booking-summary/{bookingID}', [PaymentController::class, 'bookingSummary'])
        ->name('booking.summary');
Route::post('/payment/{paymentID}/upload-receipt', [PaymentController::class, 'uploadReceipt'])
    ->name('payment.uploadReceipt');

// Receipt routes
Route::middleware(['auth', 'verified'])->group(function () {
    // View receipt in browser
    Route::get('/receipt/view/{bookingID}', [ReceiptController::class, 'view'])
        ->name('receipt.view')
        ->where('bookingID', '[A-Za-z0-9]+');
    
    // Download receipt
    Route::get('/receipt/download/{bookingID}', [ReceiptController::class, 'download'])
        ->name('receipt.download')
        ->where('bookingID', '[A-Za-z0-9]+');

    Route::get('/admin/reports/total_booking/filter', [ReportController::class, 'filterTotalBooking'])
        ->name('reports.total_booking.filter');
    
});

// Payment Pending Verification Page for Staff
Route::get('/staff/payment/record', [DashboardController::class, 'verifyBookings'])
        ->name('record.payment');
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

    // ============================
    // Vehicle Management (Admin IT only)
    // ============================
    // Vehicle Management (Admin IT only)
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('vehicles.')->group(function () {
        Route::get('/vehicles', [VehicleController::class, 'indexAdmin'])->name('index');
        Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('create');
        Route::post('/vehicles', [VehicleController::class, 'store'])->name('store');
        Route::get('/vehicles/{id}/edit', [VehicleController::class, 'edit'])->name('edit');
        Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('update');
        Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('destroy');
    });
    
    // ============================
    // Report Routes (Admin IT only)
    // ============================
    Route::middleware(['auth','role:admin'])
    ->prefix('admin/reports')
    ->name('reports.')
    ->group(function () {

        // Page utama report (ada dropdown)
        Route::get('/', [ReportController::class, 'index'])->name('index');

        // ✅ FIX: Route untuk show report by category (untuk halaman penuh)
        Route::get('/{category}', [ReportController::class, 'show'])->name('show');
        
        // AJAX untuk tukar kategori tanpa reload page
        Route::get('/{category}/ajax', [ReportController::class, 'show'])->name('ajax');

        // ✅ FIX: Filter routes (tukar ke POST untuk form submission)
        Route::post('/total_booking/filter', [ReportController::class, 'filterTotalBooking'])
            ->name('total_booking.filter');

        // ✅ ADD: Filter untuk Revenue (belum ada)
        Route::post('/revenue/filter', [ReportController::class, 'filterRevenue'])
            ->name('revenue.filter');

        Route::post('/top_college/filter', [ReportController::class, 'filterTopCollege'])
            ->name('top_college.filter');

        // ✅ FIX: Export routes structure (remove duplicate /reports/)
        Route::get('/top_college/export-pdf', [ReportController::class, 'exportTopCollegePdf'])
            ->name('top_college.exportPdf');

        Route::get('/top_college/export-excel', [ReportController::class, 'exportTopCollegeExcel'])
            ->name('top_college.exportExcel');

        Route::get('/total_booking/export-pdf', [ReportController::class, 'exportTotalBookingPdf'])
            ->name('total_booking.exportPdf');

        Route::get('/total_booking/export-excel', [ReportController::class, 'exportTotalBookingExcel'])
            ->name('total_booking.exportExcel');

        // ✅ Export Revenue 
        Route::get('/revenue/export-pdf', [ReportController::class, 'exportRevenuePdf'])
            ->name('revenue.exportPdf'); 
        Route::get('/revenue/export-excel', [ReportController::class, 'exportRevenueExcel'])
            ->name('revenue.exportExcel');
    });
});

    // ============================
    // Payment Management Routes (Admin/Salesperson)
    // ============================
    Route::middleware(['auth', 'role:admin']) // Admin access
    ->prefix('admin/payments')
    ->name('admin.payments.')
    ->group(function () {
        // List all payments (with optional status filter)
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        
        // Filter by status
        Route::get('/status/{status}', [PaymentController::class, 'index'])->name('status');
        
        // View payment details
        Route::get('/{paymentID}', [PaymentController::class, 'view'])->name('view');
        
        // Admin approve payment
        Route::post('/{paymentID}/approve', [PaymentController::class, 'approve'])->name('approve');
        
        // Admin reject payment  
        Route::post('/{paymentID}/reject', [PaymentController::class, 'reject'])->name('reject');
        
        // View receipt
        Route::get('/{paymentID}/receipt', [PaymentController::class, 'viewReceipt'])->name('receipt');
    });

    //================
    // Payment routes
    //================
    Route::get('/payment/{bookingID}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{bookingID}/submit', [PaymentController::class, 'submit'])->name('payment.submit');

    //==============
    //Payment Verify
    //==============
    Route::middleware(['auth', 'role:salesperson'])->group(function () {
    Route::put('/salesperson/payments/{paymentID}/update-status', [PaymentController::class, 'updateStatus'])
        ->name('payment.updateStatus');
    });
    Route::post('/salesperson/payments/{paymentID}/approve', [PaymentController::class, 'approve'])
    ->name('payment.approve');
    Route::post('/salesperson/payments/{paymentID}/reject', [PaymentController::class, 'reject'])
        ->name('payment.reject');

    // ============================
    // Pickup Return
    // ============================

    // Customer boleh index, create, store, edit, update
    //Route::middleware(['auth', RoleMiddleware::class.':customer'])->group(function () {
    //    Route::resource('inspection', InspectionController::class)
    //        ->only(['index','create','store','edit','update']);
    //});

    // ============================
    // Inspection Routes
    // ============================
    // Route untuk salesperson dashboard
    Route::get('/salesperson/dashboard', function () {
        return view('salesperson.dashboard');
    })->name('salesperson.dashboard')->middleware(['auth']);

    // Atau jika guna controller:
    //Route::get('/salesperson/dashboard', [SalespersonController::class, 'dashboard'])
    //    ->name('salesperson.dashboard')
    //    ->middleware(['auth']);

    // Customer routes
    /*Route::middleware(['auth', RoleMiddleware::class.':customer'])->group(function () {
        // Resource routes (index, create, store, edit, update)
        Route::get('/customer/inspections', [InspectionController::class, 'index'])->name('customer.inspections.index');        
        // Pickup inspection
        Route::get('/booking/{id}/pickup-inspection', [InspectionController::class, 'pickupInspection'])
            ->name('inspection.pickupInspection');
        Route::post('/booking/{id}/pickup-inspection', [InspectionController::class, 'storePickupInspection'])
            ->name('inspection.storePickupInspection');

        // Return inspection
        Route::get('/booking/{id}/return-inspection', [InspectionController::class, 'returnInspection'])
            ->name('inspection.returnInspection');
        Route::post('/booking/{id}/return-inspection', [InspectionController::class, 'storeReturnInspection'])
            ->name('inspection.storeReturnInspection');
    
    });*/
    Route::middleware(['auth', RoleMiddleware::class.':customer'])->group(function () {
    // View inspections list
    Route::get('/customer/inspections', [InspectionController::class, 'customerIndex'])
        ->name('customer.inspections.index');
    
    // View single inspection
    Route::get('/customer/inspections/{id}', [InspectionController::class, 'customerShow'])
        ->name('customer.inspections.show');
    
    // Pickup inspection
    Route::get('/booking/{id}/pickup-inspection', [InspectionController::class, 'pickupInspection'])
        ->name('inspection.pickupInspection');
    Route::post('/booking/{id}/pickup-inspection', [InspectionController::class, 'storePickupInspection'])
        ->name('inspection.storePickupInspection');

    // Return inspection
    Route::get('/booking/{id}/return-inspection', [InspectionController::class, 'returnInspection'])
        ->name('inspection.returnInspection');
    Route::post('/booking/{id}/return-inspection', [InspectionController::class, 'storeReturnInspection'])
        ->name('inspection.storeReturnInspection');
});

    // Staff routes
    // ✅ BETUL - Tambah `()` selepas `group`
Route::middleware(['auth', RoleMiddleware::class.':staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::prefix('inspections')->name('inspections.')->group(function () {
        Route::get('/', [InspectionController::class, 'staffIndex'])->name('index');
    });
});


    // ============================
    // Damage Case Routes
    // ============================

    // Staff hanya index, create, store, edit, update
    Route::middleware(['auth', RoleMiddleware::class.':staff'])->group(function () {
        Route::resource('damagecase', DamageCaseController::class)
            ->only(['index','edit','update','show']); 
            // tambah 'create','store','destroy' kalau staff perlu
        Route::post('damagecase/{caseID}/resolve', [DamageCaseController::class, 'resolve'])
        ->name('damagecase.resolve');
    });
    // ============================
    // Pickup Return Button
    // ============================
    Route::post('/booking/{id}/pickup', [BookingController::class, 'markPickup'])->name('booking.pickup');
    Route::post('/booking/{id}/return', [BookingController::class, 'markReturn'])->name('booking.return');


// ============================
// Payment routes
// ============================
Route::get('/payment', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment', [PaymentController::class, 'submit'])->name('payment.submit');
Route::post('/payment/{paymentID}/upload-receipt', [PaymentController::class, 'uploadReceipt'])
    ->name('payment.uploadReceipt');
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

// ============================
// Customer Management (Admin only)
// ============================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin/customers')->name('admin.customers.')->group(function () {
        Route::get('/', [DashboardController::class, 'manageCustomers'])->name('index');
        Route::get('/create', [DashboardController::class, 'createCustomer'])->name('create');
        Route::post('/', [DashboardController::class, 'storeCustomer'])->name('store');
        Route::get('/{id}', [DashboardController::class, 'viewCustomer'])->name('show');
        Route::get('/{id}/edit', [DashboardController::class, 'editCustomer'])->name('edit');
        Route::put('/{id}', [DashboardController::class, 'updateCustomer'])->name('update');
        Route::delete('/{id}', [DashboardController::class, 'deleteCustomer'])->name('destroy');
    });
});

// Admin blacklist customer 
Route::post('/admin/customers/{userId}/blacklist', [DashboardController::class, 'toggleCustomerStatus'])
    ->name('admin.customers.blacklist');
// Blacklisted customers list
Route::get('/admin/blacklisted', [DashboardController::class, 'blacklistedCustomers'])->name('admin.blacklisted.index');

//==============================
// Commission Routes (for STAFF)
//==============================
Route::middleware(['auth'])->group(function () {
    // Staff commission routes (accessible by staff/salesperson)
    Route::get('/commission', [CommissionController::class, 'staffIndex'])->name('commission.index');
    Route::get('/commission', [CommissionController::class, 'index'])->name('commission.index');
    Route::get('/commission/create', [CommissionController::class, 'create'])->name('commission.create');
    Route::post('/commission', [CommissionController::class, 'store'])->name('commission.store');
    Route::get('/commission/{id}/edit', [CommissionController::class, 'edit'])->name('commission.edit');
    Route::put('/commission/{id}', [CommissionController::class, 'update'])->name('commission.update');
    Route::delete('/commission/{id}/receipt', [CommissionController::class, 'deleteReceipt'])
        ->name('commission.deleteReceipt');
    Route::get('/admin/commission-verify/{id}', [CommissionController::class, 'adminShow'])
    ->name('admin.commissionVerify.show')
    ->middleware('auth');
});

///========================================
// Commission Verification (for ADMIN only)
//=========================================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Admin commission routes
    Route::get('/commission-verify', [CommissionController::class, 'adminIndex'])
        ->name('commissionVerify.index');
    
    Route::get('/commission-verify/{id}', [CommissionController::class, 'adminShow'])
        ->name('commissionVerify.show');
    
    Route::post('/commission-verify/{id}/approve', [CommissionController::class, 'approve'])
        ->name('commissionVerify.approve');
    
    Route::post('/commission-verify/{id}/reject', [CommissionController::class, 'reject'])
        ->name('commissionVerify.reject');
});
// Dalam web.php
/*Route::middleware('auth')->group(function() {
    Route::get('/inspection-checklist', function() {
        return view('customer.inspections.index');
    })->name('inspection.checklist');
});*/
Route::middleware(['auth'])->group(function() {
    Route::get('/inspection-checklist', function() {
        // Redirect terus ke customer inspections index
        return redirect()->route('customer.inspections.index');
    })->name('inspection.checklist');
});


// Staff Inspection Management Routes
Route::middleware(['auth', RoleMiddleware::class.':staff'])->prefix('staff')->name('staff.')->group(function () {
    
    // Inspections - View and Manage
    Route::prefix('inspections')->name('inspections.')->group(function () {
        // Index - View ALL inspections
        Route::get('/', [InspectionController::class, 'staffIndex'])->name('index');
        
        // Show single inspection
        Route::get('/{id}', [InspectionController::class, 'show'])->name('show');
        
        // Edit inspection (form)
        Route::get('/{id}/edit', [InspectionController::class, 'staffEdit'])->name('edit');
        
        // Update inspection
        Route::put('/{id}', [InspectionController::class, 'staffUpdate'])->name('update');
        
        // Delete inspection
        Route::delete('/{id}', [InspectionController::class, 'destroy'])->name('destroy');
        
        // Verify inspection
        Route::post('/{id}/verify', [InspectionController::class, 'verify'])->name('verify');
        
        // Special views
        Route::get('/today', [InspectionController::class, 'todayInspections'])->name('today');
        Route::get('/pending', [InspectionController::class, 'pendingInspections'])->name('pending');
        Route::get('/with-damage', [InspectionController::class, 'inspectionsWithDamage'])->name('damage');
    });
});

