<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'customer';
    protected $primaryKey = 'userID';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'userID', 'referred_byCode', 'accountNumber', 'bankType', 'customerType', 'customerStatus', 'referral_count'

    ];

    // relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    // Relationship to StudentCustomer
    public function studentCustomer()
    {
        return $this->hasOne(StudentCustomer::class, 'userID', 'userID');
    }
    
    // Relationship to StaffCustomer
    public function staffCustomer()
    {
        return $this->hasOne(StaffCustomer::class, 'userID', 'userID');
    }
    
    // Dynamic relationship based on type
    public function specificCustomer()
    {
        if ($this->customerType === 'student') {
            return $this->studentCustomer;
        } elseif ($this->customerType === 'staff') {
            return $this->staffCustomer;
        }
        return null;
    }

    // NEW: Relationship with VerificationDocs
    public function verificationDocs()
    {
        return $this->hasOne(VerificationDocs::class, 'customerID', 'userID');
    }

    // Relationship to BlacklistedCust
    public function blacklistedCust()
    {
        return $this->hasOne(BlacklistedCust::class, 'customerID', 'userID');
    }
    // Check if customer is currently blacklisted
    public function isBlacklisted()
    {
        return $this->customerStatus === 'blacklisted';
    }

    // Relationship to LoyaltyCard
    public function loyaltyCard()
    {
        return $this->hasOne(LoyaltyCard::class, 'customerID', 'userID');
    }

    // Automatically create a LoyaltyCard when a Customer is created
    protected static function booted()
    {
        static::created(function ($customer) {
            $customer->loyaltyCard()->create([
                'currentStamp' => 0,
                'totalStamp' => 0,
                'redeemedStamp' => 0,
                'referralCode' => substr(strtoupper(uniqid('REF')), 0, 10),
                
            ]);
        });
    }

    // Customer has many vouchers
    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'customer_voucher', 'customerID', 'voucherCode')
                    ->withPivot('redeemed_at');
    }

}