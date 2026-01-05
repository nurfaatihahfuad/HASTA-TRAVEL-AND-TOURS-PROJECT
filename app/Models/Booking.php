<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'bookingID';
    public $incrementing = false; 
    protected $keyType = 'string';

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            // contoh: BK + tarikh + random number
            $booking->bookingID = 'BK' . date('YmdHis') . rand(10, 99);
        });
    }

    public function user() 
    { 
        return $this->belongsTo(User::class, 'userID'); 
    } 

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicleID', 'vehicleID');
    }

    public function payments() 
    { 
        return $this->hasMany(Payment::class, 'bookingID', 'bookingID'); 
    }
}