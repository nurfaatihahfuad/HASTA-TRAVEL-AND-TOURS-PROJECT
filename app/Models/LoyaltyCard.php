<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyCard extends Model
{
    protected $table = 'loyaltycard';
    protected $primaryKey = 'cardID';
    public $timestamps = false;
    protected $fillable = ['currentStamp', 'totalStamp', 'redeemedStamp', 'referralCode'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID', 'userID');
    }
}