<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyCard extends Model
{
    protected $table = 'loyaltycard';
    protected $primaryKey = 'cardID';
    public $timestamps = false;
    protected $fillable = ['currentStamp', 'totalStamp', 'redeemedStamp', 'referralCode'];

    // Constants for stamp system
    const MIN_HOURS_FOR_STAMP = 9;
    const STAMPS_FOR_REWARD = 3;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID', 'userID');
    }

    // Award a stamp for eligible booking
    public function awardStamp(): bool
    {
        $this->currentStamp += 1;
        $this->totalStamp += 1;
        
        // Check if user earned a reward (3 stamps)
        if ($this->currentStamp >= self::STAMPS_FOR_REWARD) {
            $this->redeemedStamp += 1;
            $this->currentStamp = 0; // Reset after reward
            $rewardEarned = true;
        } else {
            $rewardEarned = false;
        }
        
        $saved = $this->save();
        
        return $saved;
    }

    // Check if user has earned a reward
    public function hasEarnedReward(): bool
    {
        return $this->currentStamp >= self::STAMPS_FOR_REWARD;
    }

    /**
     * Check if booking is eligible for stamp
     * Using existing total_hours attribute from Booking model
     */
    public static function isBookingEligible($totalHours): bool
    {
        return $totalHours >= self::MIN_HOURS_FOR_STAMP;
    }
}