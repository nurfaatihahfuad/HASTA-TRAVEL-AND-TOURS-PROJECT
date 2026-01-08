<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'faculty';
    protected $primaryKey = 'facultyID';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'facultyID', 'facultyName'
    ];

    // relationship back to studentCustomer
    public function studentCustomers()
    {
        return $this->hasMany(StudentCustomer::class, 'facultyID', 'facultyID');
    }

}