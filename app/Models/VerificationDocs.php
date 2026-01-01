<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationDocs extends Model
{
    use HasFactory;

    protected $table = 'verificationdocs';
    protected $primaryKey = 'docID';
    
    protected $fillable = [
        'userID',
        'ic_file_path',
        'license_file_path',
        'matric_file_path',
        'status',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    // Relationship to Admin who verified (optional)
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by', 'userID');
    }
}