<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // Show the payment page
    public function show()
    {
        return view('payment', ['bookingID' => 1]); // Replace with dynamic ID if needed

        $depositAmount = 100;
    }

    // Handle the file upload
    public function submit(Request $request, $bookingID)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,pdf|max:2048',
            'paymentType' => 'required',
        ]);

        // Store the uploaded file
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        if($request -> paymentType == 'Deposit Payment')
        {
            $amount = 100;
        }

        else
        {
            $amount = $request -> amount;
        }

        // You can log or save this path to the database if needed
        DB::table('payment') -> insert([
            'paymentType' => $request->paymentType,
            'amount' => $amount,
            'receipt_file_path' => $path,
            'paymentStatus' => 'Pending',
            'verifiedBy' => NULL,
            'VerifiedTime' => NULL,
            'userID' => 001,
        ]);

        return back()->with('success', 'Payment proof uploaded successfully!');
    }
}
