<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StaffCustomer extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'staffcustomer';
    protected $primaryKey = 'userID';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'userID', 'staffNo'
    ];

    // relationship back to User
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    // relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'userID', 'userID');
    }
}