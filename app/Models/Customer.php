<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'customer';
    public $timestamps = false;

    protected $fillable = [
        'userID', 'referred_byCode', 'accountNumber', 'bankType', 'customerType'
    ];

    // relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    // Relationship to StudentCustomer
    public function studentCustomer()
    {
        return $this->hasOne(StudentCustomer::class, 'userID', 'userID');
    }
    
    // Relationship to StaffCustomer
    public function staffCustomer()
    {
        return $this->hasOne(StaffCustomer::class, 'userID', 'userID');
    }
    
    // Dynamic relationship based on type
    public function specificCustomer()
    {
        if ($this->customerType === 'student') {
            return $this->studentCustomer;
        } elseif ($this->customerType === 'staff') {
            return $this->staffCustomer;
        }
        return null;
    }

    // NEW: Relationship with VerificationDocs
    public function verificationDocs()
    {
        return $this->hasOne(VerificationDocs::class, 'customerID', 'userID');
    }
}