<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class StudentCustomer extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'studentcustomer';
    public $timestamps = false;

    protected $fillable = [
        'userID', 'matricNo', 'facultyID', 'collegeID'
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

    // relationship with faculty
    public function faculty()
    {
        return $this->hasOne(Faculty::class, 'facultyID', 'facultyID');
    }

    // relationship with college
    public function college()
    {
        return $this->hasOne(College::class, 'collegeID', 'collegeID');
    }
}