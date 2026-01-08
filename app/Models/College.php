<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'college';
    protected $primaryKey = 'collegeID';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'collegeID', 'collegeName'
    ];

    // relationship back to studentCustomer
    public function studentCustomers()
    {
        return $this->hasMany(StudentCustomer::class, 'collegeID', 'collegeID');
    }

}