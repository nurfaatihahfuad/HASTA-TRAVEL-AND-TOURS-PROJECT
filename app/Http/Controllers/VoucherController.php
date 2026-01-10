<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Customer;
use App\Services\VoucherService;

class VoucherController extends Controller
{
    public function redeem(Request $request)
    {
        $customer = Customer::findOrFail($request->customerID);
        $voucher = Voucher::findOrFail($request->voucherCode);

        try {
            VoucherService::redeemVoucher($customer, $voucher);
            return response()->json(['message' => 'Voucher redeemed successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}