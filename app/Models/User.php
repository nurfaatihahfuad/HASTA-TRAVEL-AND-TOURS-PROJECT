<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    // declare primary key (cus by default 'id')
    protected $primaryKey = 'userID';
    protected $table = 'user';
    public $incrementing = true; // by default true, better to set explicitly
    public $timestamps = false;

    // declare attributes 
    // primary key exists in $fillable by default, no need to add it again
    protected $fillable = [
        'password', 'name', 'noHP', 'email', 'noIC', 'userType'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // relationship with Customer
    public function customer()
    {
        return $this->hasOne(Customer::class, 'userID', 'userID');
    }

    // Direct relationship with StudentCustomer
    public function studentCustomer()
    {
        return $this->hasOne(StudentCustomer::class, 'userID', 'userID');
    }
    
    // Direct relationship with StaffCustomer  
    public function staffCustomer()
    {
        return $this->hasOne(StaffCustomer::class, 'userID', 'userID');
    }

    // Helper to get the right type
    public function getSpecificCustomerAttribute()
    {
        if (!$this->customer) return null;
        
        if ($this->customer->customerType === 'student') {
            return $this->studentCustomer;
        } elseif ($this->customer->customerType === 'staff') {
            return $this->staffCustomer;
        }
        
        return null;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // for salesperson to verify documents
    public function verificationDoc()
    {
        return $this->hasOne(VerificationDocs::class, 'userID','userID');
    }

    // Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'userID');
    }
}
