<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function show($bookingID, Request $request)
{
    $booking = Booking::with('vehicle')->findOrFail($bookingID);
    
    // Debug: Pastikan vehicle data ada
    if (!$booking->vehicle) {
        abort(404, 'Vehicle not found for this booking');
    }
    
    // Debug: Check price per hour
    if (!$booking->vehicle->price_per_hour || $booking->vehicle->price_per_hour <= 0) {
        abort(400, 'Vehicle price is not set or invalid');
    }
    
    // Kira total hours
    $pickup = Carbon::parse($booking->pickup_dateTime);
    $return = Carbon::parse($booking->return_dateTime);
    $totalHours = round($pickup->diffInHours($return));
    
    // Kira total amount
    $totalAmount = round($totalHours * $booking->vehicle->price_per_hour);
    
    // Dapatkan payment type dengan default
    $paymentType = $request->input('paymentType', 'Deposit Payment'); // Default ke Deposit
    
    // Kira amount to pay
    $amountToPay = 0;
    
    if ($paymentType === 'Full Payment') { 
        $amountToPay = $totalAmount; 
    } 
    elseif ($paymentType === 'Deposit Payment') { 
        $amountToPay = 50; 
    }
    
    // Debug info - boleh uncomment untuk test
    /*
    dd([
        'bookingID' => $bookingID,
        'vehicle_name' => $booking->vehicle->vehicleName,
        'price_per_hour' => $booking->vehicle->price_per_hour,
        'pickup' => $booking->pickup_dateTime,
        'return' => $booking->return_dateTime,
        'total_hours' => $totalHours,
        'total_amount' => $totalAmount,
        'payment_type' => $paymentType,
        'amount_to_pay' => $amountToPay
    ]);
    */
    
    return view('payment', compact(
        'booking', 
        'totalHours', 
        'totalAmount', 
        'paymentType', 
        'amountToPay'
    ));
}

     public function store(Request $request)
        {

            $request->validate([
            'paymentType' => 'required|in:Full Payment,Deposit Payment',
            'payment_proof' => 'required|file|mimes:jpeg,png,pdf|max:2048',
            'bookingID' => 'required|exists:bookings,bookingID',
            'amountPaid' => 'required|numeric',
             ]);

        // Simpan file ke storage/public/payments
        $path = $request->file('payment_proof')->store('payments', 'public');

        $booking = Booking::with('vehicle')->findOrFail($request->bookingID);
        $pickup = Carbon::parse($booking->pickup_dateTime);
        $return = Carbon::parse($booking->return_dateTime);
        $totalHours = round($pickup->diffInHours($return));
        $totalAmount = round($totalHours * $booking->vehicle->price_per_hour);

        // Simpan rekod ke DB
        Payment::create([
        'paymentID' => 'PM' . strtoupper(uniqid()),
        'bookingID'  => $request->bookingID,
        'paymentType' => $request->paymentType,
        'amountPaid' => $request->amountPaid,
        'receipt_file_path'  => $path,
        'paymentStatus'     => 'pending',
        'totalAmount' => $totalAmount,
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Payment uploaded!');
    }

    public function uploadReceipt(Request $request, $paymentID)
    {
        $payment = Payment::findOrFail($paymentID);
        
        // Delete old file if exists
        if ($payment->receipt_file_path && Storage::exists('public/' . $payment->receipt_file_path)) {
            Storage::delete('public/' . $payment->receipt_file_path);
        }
        
        // Save new file
        $path = $request->file('receipt_file')->store('receipts', 'public');
        $payment->update(['receipt_file_path' => $path]);
        
        return back()->with('success', 'Receipt uploaded successfully.');
    }

    public function bookingSummary($bookingID)
    {
        $booking = Booking::with('vehicle')->findOrFail($bookingID);

        //$payment = Payment::where('bookingID', $bookingID)->latest()->first();

        $payment = Payment::where('bookingID', $bookingID)
                  ->orderBy('paymentID', 'desc')
                  ->first();


        $pickup = \Carbon\Carbon::parse($booking->pickup_dateTime);
        $return = \Carbon\Carbon::parse($booking->return_dateTime);
        $totalHours = round($pickup->diffInHours($return));
        $totalPayment = round($totalHours * $booking->vehicle->price_per_hour);

        return view('customers.BookingView.bookingView', compact('booking','payment','totalHours','totalPayment'));
    }

    public function submit(Request $request, $bookingID)
    {
        $request->validate([
            'paymentType' => 'required|in:Full Payment,Deposit Payment',
            'payment_proof' => 'required|file|mimes:jpeg,png,pdf|max:2048',
             ]);

        $path = $request->file('payment_proof')->store('payments', 'public');

        $booking = Booking::with('vehicle')->findOrFail($bookingID);
        $pickup = Carbon::parse($booking->pickup_dateTime);
        $return = Carbon::parse($booking->return_dateTime);
        $totalHours = round($pickup->diffInHours($return));
        $totalAmount = round($totalHours * $booking->vehicle->price_per_hour);

        $amount = $request->paymentType === 'Full Payment' ? $totalAmount : 50;

        Payment::create([ 
            'paymentID' => 'PM' . strtoupper(uniqid()), // random ID
            'bookingID' => $bookingID, 
            'paymentType' => $request->paymentType, 
            'amountPaid' => $amount, 
            'receipt_file_path' => $path, 
            'paymentStatus' => 'pending', 
            'totalAmount' => $totalAmount,
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Payment submitted successfully!');
        /*
        return redirect()->route('payment.show', ['paymentType' => $paymentType])
        ->with('success', "Payment submitted successfully. Amount: RM{$amount}");
        */ 
    }


    public function updateStatus(Request $request, $paymentID): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,successful,rejected',
        ]);

        $payment = Payment::findOrFail($paymentID);
        $status = strtolower(trim($request->input('status')));
        
        $payment->paymentStatus = $status;
        $payment->save();

        return back()->with('success', "Payment {$paymentID} updated to " . ucfirst($status));
    }

    public function approve($paymentID)
    {

        $payment = Payment::findOrFail($paymentID);
        $payment->paymentStatus = 'approved';
        $payment->save();

        return redirect()->back()->with('success', 'Payment has been approved.');
    }

    public function reject($paymentID)
    {
        $payment = Payment::findOrFail($paymentID);
        $payment->paymentStatus = 'rejected';
        $payment->save();

        return redirect()->back()->with('success', 'Payment has been rejected.');
    }

            /**
             * Reset booking to pending (additional method if needed)
             */
    public function resetToPending($paymentID): RedirectResponse
    {
        $payment = Payment::findOrFail($bookingID);
        $payment->paymentStatus = 'pending';
        $payment->save();

        return redirect()->back()->with('success', 'Payment has been reset to pending status.');
    }

    //ni terkait dengan admin dashboard-dina--------------------------------------------------------------------------
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Payment::with(['booking.vehicle', 'booking.user'])
            ->orderBy('created_at', 'desc');
        
        // Filter by status if not 'all'
        if ($status !== 'all') {
            $query->where('paymentStatus', $status);
        }
        
        // Add search if needed
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('paymentID', 'LIKE', "%{$search}%")
                ->orWhere('bookingID', 'LIKE', "%{$search}%")
                ->orWhereHas('booking.user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                });
            });
        }
        
        // Use pagination for better performance
        $payments = $query->paginate(20)->withQueryString();
        
        // Get statistics for the dashboard
        $stats = [
            'total' => Payment::count(),
            'pending' => Payment::where('paymentStatus', 'pending')->count(),
            'approved' => Payment::where('paymentStatus', 'approved')->count(),
            'rejected' => Payment::where('paymentStatus', 'rejected')->count(),
        ];
        
        return view('admin.payments.index', compact('payments', 'status', 'stats'));
    }
   

}