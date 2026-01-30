<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'staffID';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'staffID',
        'staffRole',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship back to User
    public function user()
    {
        return $this->belongsTo(User::class, 'staffID', 'userID');
    }

    // Relationship to verificationDocs
    public function document()
    {
        return $this->hasOne(VerificationDocs::class, 'staffID', 'verified_by');
    }
}