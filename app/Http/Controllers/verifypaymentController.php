<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VerifyPaymentController extends Controller
{
    public function index()
{
    $payments = DB::table('payment')->get();
    return view('verifypayment', compact('payments'));
}

    public function verify(Request $request, $paymentID)
    {
        $action = $request->input('action');
        $status = $action === 'Approve' ? 'Approve' : 'Rejected';

        DB::table('payment')->where('paymentID', $paymentID)->update([
            'paymentStatus' => $status,
            'verifiedBy'    => Auth::user()->name ?? 'Staff',
            'VerifiedTime'  => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->back()->with('success', "Payment has been {$status}.");
    }
}
