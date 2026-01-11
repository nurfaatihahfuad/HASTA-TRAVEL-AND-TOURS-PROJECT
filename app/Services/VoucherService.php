<?php

use App\Models\Voucher;
use App\Models\Customer;

class VoucherService
{
    public static function redeemVoucher(Customer $customer, Voucher $voucher)
    {
        // Check voucher status
        if ($voucher->status !== 'active') {
            throw new \Exception("Voucher not active");
        }

        // Check if already redeemed by this customer
        if ($customer->vouchers()->where('voucherCode', $voucher->voucherCode)->exists()) {
            throw new \Exception("Voucher already redeemed by this customer");
        }

        // Check limit
        if ($voucher->limit <= 0) {
            $voucher->status = 'expired';
            $voucher->save();
            throw new \Exception("Voucher redemption limit reached");
        }

        // Attach voucher to customer (pivot table entry)
        $customer->vouchers()->attach($voucher->voucherCode, [
            'redeemed_at' => now(),
        ]);

        // Decrement limit
        $voucher->limit -= 1;
        if ($voucher->limit == 0) {
            $voucher->status = 'redeemed';
        }
        $voucher->save();
    }
}