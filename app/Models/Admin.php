<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    //protected $primaryKey = 'adminID';
    //protected $keyType = 'string';
    //public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'adminID',
        'adminType'
    ];

    // Relationship back to User
    public function user()
    {
        return $this->belongsTo(User::class, 'adminID', 'userID');
    }
}