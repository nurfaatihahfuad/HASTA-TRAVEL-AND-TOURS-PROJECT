<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment'; 
    protected $primaryKey = 'paymentID';
    public $incrementing = false; 
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'paymentID',
        'bookingID',
        'paymentType',
        'amountPaid',
        'receipt_file_path',
        'paymentStatus',
        'totalAmount',
        //'verifiedBy',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID');
    }

    /*
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
    

    private function generatePaymentID()
    {
        return 'PM' . strtoupper(uniqid()); 
    }
    */

}
