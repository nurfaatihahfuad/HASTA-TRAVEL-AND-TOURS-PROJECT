<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // Show the payment page
    public function show($bookingID)
    {
        $booking = Booking::with('vehicle')->findOrFail($bookingID);

        $pickup = Carbon::parse($booking->pickup_dateTime);
        $return = Carbon::parse($booking->return_dateTime);
        $totalHours = $pickup->diffInHours($return);
        $totalPayment = $totalHours * $booking->vehicle->price_per_day;

        return view('payment', [
            'booking' => $booking,
            'bookingID' => $booking->id,
            'totalHours' => $totalHours,
            'totalPayment' => $totalPayment, ]
        ); 
    }

    public function submit(Request $request, $bookingID)
    {
        $request->validate([
            'paymentType' => 'required|string',
            'payment_proof' => 'required|file|mimes:jpeg, png, pdf|max:2048',
        ]);

        $booking = Booking::findOrFail($bookingID);

        $pickup = Carbon::parse($booking->pickup_dateTime);
        $return = Carbon::parse($booking->return_dateTime);
        $totalHours = $pickup->diffInHours($return);
        $totalPayment = $totalHours * $booking->vehicle->price_per_day;

        $path = $request->file('payment_proof')->store('payment', 'public');

        Payment::create([
            'booking_id' => $booking->id, 
            'paymentType' => $request->paymentType,
            'amount' => $amount, 
            'receipt_file_path' => $path,
            'paymentStatus' => 'Pending',
            'userID' => auth()->id(),
        ]);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Payment submitted Successfully!');
    }
}