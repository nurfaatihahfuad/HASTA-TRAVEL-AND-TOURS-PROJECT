<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'feedbackID';
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        
        'rate',
        'reviewSentences',
        'bookingID',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }
}
