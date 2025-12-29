<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';

    protected $primaryKey = 'vehicleID';

    protected $fillable = [
        'brand', 'model', 'year', 'type', 'seats',
        'price_per_day', 'available', 'image_url', 
        'description', 'created_at' , 'updated_at', 'image_url'
    ];
}

