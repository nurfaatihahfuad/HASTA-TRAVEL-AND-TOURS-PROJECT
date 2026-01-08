<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory ;
    
    protected $table = 'vehicles';

    protected $primaryKey = 'vehicleID';

    protected $fillable = [
        'vehicleName', 'plateNo', 'year',
        'price_per_day', 'available', 'image_url', 
        'description', 'created_at' , 'updated_at', 'image_url'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'vehicleID');
    }
}



