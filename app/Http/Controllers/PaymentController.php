<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function show($bookingID, Request $request)
    {
        $booking = Booking::with('vehicle')->findOrFail($bookingID);

        $vehicle = [
            'price_per_hour' => $booking->vehicle->price_per_hour,
        ];

        $pickup = Carbon::parse($booking->pickup_dateTime);
        $return = Carbon::parse($booking->return_dateTime);
        $totalHours = $pickup->diffInHours($return);
        $totalAmount = round($totalHours * $vehicle['price_per_hour']);
        $vehicleName = $booking->vehicle->vehicleName;
        $paymentType = $request->input('paymentType'); 
        $amountToPay = null; 
        
        if ($paymentType === 'Full Payment') 
        { 
            $amountToPay = $totalAmount; 
        } 
        elseif ($paymentType === 'Deposit Payment') 
        { 
            $amountToPay = 50; 
        } 
        return view('payment', compact('booking', 'totalHours', 'totalAmount', 'paymentType', 'amountToPay')); 
        //return redirect()->route('customer.dashboard')->with('success', 'Payment saved!');
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
        $totalHours = $pickup->diffInHours($return);
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

    public function bookingSummary($bookingID)
    {
        $booking = Booking::with('vehicle')->findOrFail($bookingID);

        //$payment = Payment::where('bookingID', $bookingID)->latest()->first();

        $payment = Payment::where('bookingID', $bookingID)
                  ->orderBy('paymentID', 'desc')
                  ->first();


        $pickup = \Carbon\Carbon::parse($booking->pickup_dateTime);
        $return = \Carbon\Carbon::parse($booking->return_dateTime);
        $totalHours = $pickup->diffInHours($return);
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
        $totalHours = $pickup->diffInHours($return);
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

        $booking->bookingStatus = 'payment_submitted';
        //$booking->save();

        return redirect()->route('customer.dashboard')->with('success', 'Payment submitted successfully!');
        /*
        return redirect()->route('payment.show', ['paymentType' => $paymentType])
        ->with('success', "Payment submitted successfully. Amount: RM{$amount}");
        */ 
    }

   

}
