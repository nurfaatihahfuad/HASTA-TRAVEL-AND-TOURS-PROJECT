<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';

    protected $primaryKey = 'vehicleID';

    protected $fillable = [
        'vehicleName', 'plateNo', 'year',
        'price_per_day', 'price_per_hour','available', 'image_url', 
        'description', 'created_at' , 'updated_at', 'image_url'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'vehicleID');
    }
}



