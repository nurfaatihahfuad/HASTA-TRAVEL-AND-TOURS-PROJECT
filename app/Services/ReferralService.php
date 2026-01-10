<?php
namespace App\Services;
use App\Models\Customer;
use App\Models\LoyaltyCard;
use App\Models\Voucher;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    // Pre-defined voucher configurations
    const VOUCHER_TYPES = [
        'snfpisangcheese' => [
            'type' => 'vendor',
            'expiry_days' => 30,
            'name' => 'RM2 SNF Pisang Cheese Discount',
        ],
        'pak_atong_cafe' => [
            'type' => 'vendor', 
            'expiry_days' => 30,
            'name' => 'RM5 Pak Atong Cafe Discount',
        ],
        'rental_3days' => [
            'type' => 'rental',
            'expiry_days' => 60,
            'name' => '3 Free Rental Days',
        ]
    ];

    // Milestone mapping
    const MILESTONES = [
        5 => 'snfpisangcheese',   // 5 referrals
        10 => 'pak_atong_cafe',   // 10 referrals  
        20 => 'rental_3days',     // 20 referrals
    ];

    public static function issueVoucher(Customer $customer, string $voucherKey)
    {
        $config = self::VOUCHER_TYPES[$voucherKey] ?? self::VOUCHER_TYPES['snfpisangcheese'];
        
        // Create the voucher
        $voucher = Voucher::create([
            'voucherCode' => self::generateVoucherCode($voucherKey),
            'type'        => $config['type'],
            'expiryDate'  => now()->addDays($config['expiry_days']),
            'limit'       => 1,
            'status'      => 'active',
        ]);

        // Attach voucher to customer via pivot table
        $customer->vouchers()->attach($voucher->voucherCode, [
            'redeemed_at' => null,
        ]);

        Log::info("Voucher issued: {$voucherKey} to customer {$customer->userID}");

        return $voucher;
    }

    // Generate meaningful voucher codes
    private static function generateVoucherCode($voucherKey)
    {
        $prefixes = [
            'snfpisangcheese' => 'SNF2',
            'pak_atong_cafe' => 'PA05',
            'rental_3days' => 'RD03',
        ];
        
        $prefix = $prefixes[$voucherKey] ?? 'VOUCH';
        
        return $prefix . strtoupper(Str::random(6));
    }

    public static function applyReferral(Customer $newCustomer, ?string $referralCode)
    {
        if (!$referralCode) {
            Log::info("No referral code for customer {$newCustomer->userID}");
            return;
        }

        // Prevent self-referral
        if ($newCustomer->loyaltyCard && $newCustomer->loyaltyCard->referralCode === $referralCode) {
            Log::info("Self-referral prevented for {$newCustomer->userID}");
            return;
        }

        // Find the referrer
        $referrer = Customer::whereHas('loyaltyCard', function ($q) use ($referralCode) {
            $q->where('referralCode', $referralCode);
        })->first();

        if ($referrer) {
            $oldCount = $referrer->referral_count ?? 0;
            $referrer->increment('referral_count');
            $referrer->refresh();
            $newCount = $referrer->referral_count;

            Log::info("Referral: {$referrer->userID} count {$oldCount}→{$newCount}");

            // Check each milestone
            foreach (self::MILESTONES as $threshold => $voucherKey) {
                if ($oldCount < $threshold && $newCount >= $threshold) {
                    self::issueVoucher($referrer, $voucherKey);
                    Log::info("Awarded {$voucherKey} for {$threshold} referrals");
                }
            }
        } else {
            Log::warning("Referrer not found: {$referralCode}");
        }
    }

    // Helper to get voucher display details
    public static function getVoucherDisplayDetails($voucherCode)
    {
        $prefix = substr($voucherCode, 0, 4);
        
        $mapping = [
            'SNF2' => [
                'vendor' => 'SNF Pisang Cheese', 
                'benefit' => 'RM2 discount',
                'name' => 'SNF Pisang Cheese Voucher'
            ],
            'PA05' => [
                'vendor' => 'Pak Atong Cafe', 
                'benefit' => 'RM5 discount',
                'name' => 'Pak Atong Cafe Voucher'
            ],
            'RD03' => [
                'vendor' => 'Vehicle Rental', 
                'benefit' => '3 free rental days',
                'name' => 'Free Rental Voucher'
            ]
        ];
        
        return $mapping[$prefix] ?? [
            'vendor' => 'Partner', 
            'benefit' => 'Special offer',
            'name' => 'Voucher'
        ];
    }
}