<?php

// app/Http/Controllers/CustomerProfileController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\College;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;

class CustomerProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $colleges = College::all();

        // Eager load all relationships
        $customer = Customer::with([
            'loyaltyCard',
            'vouchers' => function($query) {
                $query->orderBy('expiryDate', 'asc');
            },
            'studentCustomer.faculty',
            'studentCustomer.college',
            'staffCustomer',
            'verificationDocs'
        ])->where('userID', $user->userID)->first();
        
        // Debug - check what's loaded
        \Log::info('Customer profile loaded:', [
            'userID' => $user->userID,
            'has_customer' => $customer ? 'yes' : 'no',
            'loyalty_card' => $customer && $customer->loyaltyCard ? 'yes' : 'no',
            'vouchers_count' => $customer ? $customer->vouchers->count() : 0,
            'referral_count' => $customer ? $customer->referral_count : 0
        ]);
        
        if (!$customer) {
            return redirect()->route('home')->with('error', 'Customer profile not found!');
        }
        
        return view('customers.profile', compact('user', 'customer','colleges'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'noHP' => 'required|string|max:20',
            'accountNumber' => 'required|string|max:50',
            'bankType' => 'required|string|max:50',
            'collegeID' => 'required|string|max:50',
        ]);
        
        $user->update([
            'name' => $validated['name'], 
            'noHP' => $validated['noHP']
        ]);
        
        $customer->update([
            'accountNumber' => $validated['accountNumber'],
            'bankType' => $validated['bankType'],
        ]);
        if ($customer->customerType === 'student' && $customer->studentCustomer) {
            $customer->studentCustomer->update([
                'collegeID' => $request->collegeID,
            ]);
        }
        
        return back()->with('success', 'Profile updated successfully!');
    }

    public function redeem(Request $request, $voucherCode)
    {
        \Log::info("🎯 === CONTROLLER REDEMPTION START ===");
        \Log::info("🎯 Customer: " . Auth::user()->userID);
        \Log::info("🎯 Voucher: {$voucherCode}");
        
        $customer = Auth::user()->customer;
        
        // LOG 1: Check current state
        $before = DB::table('customer_voucher')
            ->where('customerID', $customer->userID)
            ->where('voucherCode', $voucherCode)
            ->first();
        
        \Log::info("📊 BEFORE - Row exists: " . ($before ? 'YES' : 'NO'));
        if ($before) {
            \Log::info("📊 BEFORE - redeemed_at: " . ($before->redeemed_at ?? 'NULL'));
        }
        
        if (!$before) {
            \Log::error("❌ No pivot row!");
            return redirect()->route('customer.profile')->with('error', 'Voucher not found.');
        }
        
        if ($before->redeemed_at) {
            \Log::warning("⚠️ Already redeemed!");
            return redirect()->route('customer.profile')->with('error', 'Already used.');
        }
        
        // LOG 2: Try the EXACT SAME update that worked in SQL test
        \Log::info("🔄 Attempting UPDATE with DB::raw('NOW()')...");
        
        $updated = DB::table('customer_voucher')
            ->where('customerID', $customer->userID)
            ->where('voucherCode', $voucherCode)
            ->update([
                'redeemed_at' => DB::raw('NOW()')  // Use DB::raw like the SQL test
            ]);
        
        \Log::info("🔄 Update result: {$updated} rows affected");
        
        // LOG 3: Check PDO error if update failed
        if ($updated === 0) {
            $error = DB::getPdo()->errorInfo();
            \Log::error("❌ PDO Error: ", $error);
        }
        
        // LOG 4: Check after state
        $after = DB::table('customer_voucher')
            ->where('customerID', $customer->userID)
            ->where('voucherCode', $voucherCode)
            ->first();
        
        \Log::info("📊 AFTER - redeemed_at: " . ($after->redeemed_at ?? 'NULL'));
        
        if ($updated > 0) {
            \Log::info("✅ SUCCESS!");
            return redirect()->route('customer.profile')
                ->with('success', 'Voucher marked as used!');
        } else {
            \Log::error("❌ FAILED: 0 rows updated");
            return redirect()->route('customer.profile')
                ->with('error', 'Failed to update. Check logs.');
        }
    }

    // To show booking history of customer
    public function bookingIndex(Request $request) {
        
        $query = Booking::with('vehicle')
            ->where('userID', auth()->id())
            ->latest();

        // ✅ Filter by date range (pickup date)
        if ($request->filled('from')) {
            $query->whereDate('pickup_dateTime', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('pickup_dateTime', '<=', $request->to);
        }

        // Search by booking ID
        if ($request->search) {
            $query->where('bookingID', 'like', '%' . $request->search . '%');
        }
        

        // Filter by status
        if ($request->status) {
            $query->where('bookingStatus', $request->status);
        }

        $bookings = $query
        ->with('inspections')
        ->orderBy('pickup_dateTime', 'desc')
        ->paginate(6)
        ->withQueryString();

    // ✅ Auto mark completed
    foreach ($bookings as $booking) {
        if ($booking->shouldAutoComplete()) {
            $booking->update(['bookingStatus' => 'completed']);
        }
    }


        return view('customers.BookingHistory.index', compact('bookings'));
    }

    public function bookingShow(Booking $booking)
    {
        abort_if($booking->userID !== auth()->id(), 403);

        // ambil semua payment untuk booking tu
        $payments = Payment::where('bookingID', $booking->bookingID)->get();

        // ambil payment latest
        $payment = $payments->last();

        // kira total harga
        $pickup = Carbon::parse($booking->pickup_dateTime);
        $return = Carbon::parse($booking->return_dateTime);
        $totalHours = $pickup->diffInHours($return);
        $totalPayment = round($totalHours * $booking->vehicle->price_per_hour);

        $booking->load(['vehicle', 'payments']);


        return view('customers.BookingHistory.show', compact('booking', 'payment', 'payments', 'totalHours', 'totalPayment'));
    }
}