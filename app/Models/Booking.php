<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
        'userID',
        'vehicleID',
        'pickup_dateTime',
        'return_dateTime',
        'pickupAddress',
        'returnAddress',
        'voucherCode',
        'bookingStatus',
        'created_at', 
        'updated_at',
    ];

    public function user() 
    { 
        return $this->belongsTo(User::class, 'userID'); 
    } 

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

