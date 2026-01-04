<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $table = 'inspection';
    protected $primaryKey = 'inspectionid';
    public $timestamps = false;

    protected $fillable = [
        'inspDate', 'carCondition', 'mileageReturned', 'fuelLevel',
        'damageDetected', 'remark', 'evidence', 'userID', 'roleName', 'bookingID'
    ];
}