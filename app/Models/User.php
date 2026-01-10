<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Override default Laravel expectation
     */
    protected $table = 'user';        // table sebenar dalam DB
    protected $primaryKey = 'userID'; // PK sebenar
    protected $keyType = 'string';    // kalau PK bukan integer
    public $incrementing = false;     // kalau PK bukan auto increment
    public $timestamps = false;       // kalau tiada created_at / updated_at

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'password', 'name', 'noHP', 'email', 'noIC', 'userType'
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Ensure Laravel uses userID for authentication
     */
    public function getAuthIdentifierName()
    {
        return 'userID';
    }

    public function getAuthIdentifier()
    {
        return $this->userID;
    }

    /**
     * Auto-generate userID prefix based on userType
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $prefix = match ($model->userType) {
                'customer' => 'UC',
                'admin'    => 'UD',
                'staff'    => 'US',
                default    => 'U'
            };

            $lastUser = User::where('userID', 'LIKE', $prefix.'%')
                            ->orderBy('userID', 'desc')
                            ->first();

            $nextNum = $lastUser
                ? intval(substr($lastUser->userID, 2)) + 1
                : 1;

            $model->userID = $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Relationships
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'userID', 'userID');
    }

    public function studentCustomer()
    {
        return $this->hasOne(StudentCustomer::class, 'userID', 'userID');
    }

    public function staffCustomer()
    {
        return $this->hasOne(StaffCustomer::class, 'userID', 'userID');
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'staffID', 'userID');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'adminID', 'userID');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'userID');
    }

    //Auni tambah
    public function commissions()
    {
        return $this->hasMany(Commission::class, 'userID', 'userID');
    }

    public function verificationDocs()
    {
        return $this->hasOneThrough(
            VerificationDocs::class,
            Customer::class,
            'userID',      // Foreign key on Customer table
            'customerID',  // Foreign key on VerificationDocs table
            'userID',      // Local key on User table
            'userID'       // Local key on Customer table
        );
    }

    /**
     * Helpers
     */
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

    public function isCustomer()
    {
        return $this->userType === 'customer';
    }

    public function getFullRoleAttribute()
    {
        if ($this->userType === 'staff' && $this->staff) {
            return 'staff.' . $this->staff->staffRole;
        }
        return $this->userType;
    }

    public function isAdmin()
    {
        return $this->userType === 'admin';
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

}
