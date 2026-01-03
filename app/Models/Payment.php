<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments'; // default plural
    protected $primaryKey = 'id';

    protected $fillable = [
        'booking_id',
        'paymentType',
        'amount',
        'receipt_file_path',
        'paymentStatus',
        'userID',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
