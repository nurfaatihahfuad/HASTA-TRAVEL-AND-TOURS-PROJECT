<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function show(Request $request)
    {
        // Dummy data (boleh tukar ikut keperluan)
        $vehicle = [
            'brand' => 'Toyota',
            'model' => 'Vios',
            'price_per_day' => 20,
        ];

        $pickup = Carbon::parse('2026-01-03 10:00:00');
        $return = Carbon::parse('2026-01-03 15:00:00');
        $totalHours = $pickup->diffInHours($return);
        $totalPayment = $totalHours * $vehicle['price_per_day'];

        $paymentType = $request->input('paymentType'); 
        $amountToPay = null; 
        
        if ($paymentType === 'Full') 
        { 
            $amountToPay = $totalPayment; 
        } 
        elseif ($paymentType === 'Deposit') 
        { 
            $amountToPay = 20; 
        } 
        return view('payment', compact('vehicle', 'totalHours', 'totalPayment', 'paymentType', 'amountToPay')); 
    }

    public function submit(Request $request)
    {
        $vehicle = [
            'brand' => 'Toyota',
            'model' => 'Vios',
            'price_per_day' => 20,
        ];

        $pickup = Carbon::parse('2026-01-03 10:00:00');
        $return = Carbon::parse('2026-01-03 15:00:00');
        $totalHours = $pickup->diffInHours($return);
        $totalPayment = $totalHours * $vehicle['price_per_day'];

        $paymentType = $request->input('paymentType');
        $amount = $paymentType === 'Full' ? $totalPayment : 20;

        $request->validate([
            'paymentType' => 'required|string',
            'payment_proof' => 'required|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        return redirect()->route('payment.show', ['paymentType' => $paymentType])
        ->with('success', "Payment submitted successfully. Amount: RM{$amount}");
    }

}
