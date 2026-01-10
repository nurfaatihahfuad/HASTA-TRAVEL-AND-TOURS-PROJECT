<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $table = 'commission';

    protected $primaryKey = 'commissionID';
    public $incrementing = false;
    protected $keyType = 'string';
    
    public $timestamps = false;

    protected $fillable = [
        'commissionID',
        'commissionType',
        'receipt_file_path',
        'status',
        'appliedDate',
        'amount',
        'accountNumber', 
        'bankName',    
        'userID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}