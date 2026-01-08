<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking'; // atau 'bookings' kalau migration kau plural
    protected $primaryKey = 'bookingID'; // adjust kalau DB guna 'id'

    protected $fillable = [
        'userID',
        'vehicleID',
        'pickup_dateTime',
        'return_dateTime',
        'pickupAddress',
        'returnAddress',
        'voucherCode',
        'bookingStatus',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicleID');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'bookingID');
    }
}
