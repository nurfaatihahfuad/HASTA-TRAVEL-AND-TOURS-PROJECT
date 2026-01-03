<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationDocs extends Model
{
    use HasFactory;

    protected $table = 'verificationdocs';
    protected $primaryKey = 'docID';
    protected $keyType = 'string';
    public $incrementing = false;
    
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

    // prefix for docID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $prefix = 'D';

            $lastDoc = VerificationDocs::where('docID', 'LIKE', $prefix.'%')
                                       ->orderBy('docID', 'desc')
                                       ->first();

            $nextNum = $lastDoc
                ? intval(substr($lastDoc->docID, 1)) + 1
                : 1;

            $model->docID = $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
        });
    }


    // Relationship to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'userID', 'userID');
    }

    // Relationship to Salesperson who verified (optional)
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by', 'userID');
    }
}