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
    protected $keyType = 'string';
    public $incrementing = false; // by default true
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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // for userID prefix
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Decide prefix based on userType
            $prefix = match ($model->userType) {
                'customer' => 'UC',
                'admin'    => 'UD',
                'staff'    => 'US',
                default    => 'U'
            };

            // Find last ID with this prefix
            $lastUser = User::where('userID', 'LIKE', $prefix.'%')
                            ->orderBy('userID', 'desc')
                            ->first();

            $nextNum = $lastUser
                ? intval(substr($lastUser->userID, 2)) + 1
                : 1;

            // Assign new ID
            $model->userID = $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
        });
    }


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

    // relationship with Staff
    public function staff()
    {
        return $this->hasOne(Staff::class, 'staffID', 'userID');
    }

    // Add role checking methods to User model (add these methods)
    public function hasRole($role)
    {
        if (str_contains($role, '.')) {
            [$userType, $staffRole] = explode('.', $role, 2);
            
            if ($this->userType !== $userType) {
                return false;
            }
            
            if ($this->userType === 'staff' && $this->staff) {
                return $this->staff->staffRole === $staffRole;
            }
            
            return false;
        }
        
        return $this->userType === $role;
    }

    public function isSalesperson()
    {
        return $this->userType === 'staff' && 
               $this->staff && 
               $this->staff->staffRole === 'salesperson';
    }

    public function isRunner()
    {
        return $this->userType === 'staff' && 
               $this->staff && 
               $this->staff->staffRole === 'runner';
    }

    public function getFullRoleAttribute()
    {
        if ($this->userType === 'staff' && $this->staff) {
            return 'staff.' . $this->staff->staffRole;
        }
        return $this->userType;
    }

    // Relationship to Admin
    public function admin()
    {
        return $this->hasOne(Admin::class, 'adminID', 'userID');
    }

    public function isITadmin()
    {
        return $this->userType === 'admin' &&
                $this->admin &&
                $this->admin->adminType === 'IT';
    }

    public function isFinanceAdmin()
    {
        return $this->userType === 'admin' &&
                $this->admin &&
                $this->admin->adminType === 'finance';
    }

    // Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'userID');
    }
}
