<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /*
    public function bookingSummary($bookingID)
    {
        // Ambil booking + vehicle
        $booking = Booking::with('vehicle')->findOrFail($bookingID);

        // Ambil payment record latest untuk booking tu
        $payment = Payment::where('bookingID', $bookingID)->latest()->first();

        // Kira total hours & payment
        $pickup = Carbon::parse($booking->pickup_dateTime);
        $return = Carbon::parse($booking->return_dateTime);
        $totalHours = $pickup->diffInHours($return);
        $totalPayment = round($totalHours * $booking->vehicle->price_per_hour);

        if ($payment && $payment->amountPaid >= $totalPayment) 
        { 
            $booking->bookingStatus = 'successful'; 
        } 
        else 
        { 
            $booking->bookingStatus = 'pending'; 
        } 
        $booking->save();

        // Pass ke view
        return view('customers.BookingView.bookingView', compact('booking','payment','totalHours','totalPayment'));
    }
    */
    
    public function bookingSummary($bookingID)
    {
        $booking = Booking::with(['vehicle','user'])->findOrFail($bookingID);

        // ambil semua payment untuk booking tu
        $payments = Payment::where('booking_id', $booking->id)->get();

        // ambil payment latest
        $payment = $payments->last();

        // kira total harga
        $pickup = Carbon::parse($booking->pickup_dateTime);
        $return = Carbon::parse($booking->return_dateTime);
        $totalHours = $pickup->diffInHours($return);
        $totalPayment = round($totalHours * $booking->vehicle->price_per_hour);

        return view('customers.BookingView.bookingView', compact(
            'booking','payment','payments','totalHours','totalPayment'
        ));
    }


}
