<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'bookingID';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'userID',
        'vehicleID',
        'pickup_dateTime',
        'return_dateTime',
        'pickupAddress',
        'returnAddress',
        'voucherCode',
        'bookingStatus',
        'created_at', 
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            // contoh: BK + tarikh + random number
            $booking->bookingID = 'BK' . date('YmdHis') . rand(10, 99);
        });
    }

    public function user() 
    { 
        return $this->belongsTo(User::class, 'userID'); 
    } 

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicleID', 'vehicleID');
    }

    public function payments() 
    { 
        return $this->hasMany(Payment::class, 'bookingID', 'bookingID'); 
    }

    public function getTotalHoursAttribute()
    {
        if ($this->pickup_dateTime && $this->return_dateTime) {
            return Carbon::parse($this->pickup_dateTime)
                ->diffInHours(Carbon::parse($this->return_dateTime));
        }
        return 0;
    }

    public function getTotalPriceAttribute()
    {
        if ($this->vehicle && $this->pickup_dateTime && $this->return_dateTime) {
            $pickup = Carbon::parse($this->pickup_dateTime);
            $return = Carbon::parse($this->return_dateTime);

            $hours = $pickup->diffInHours($return);
            $days  = $pickup->diffInDays($return);

            // kalau lebih 24 jam, kira ikut hari
            if ($days >= 1) {
                return $days * ($this->vehicle->price_per_day ?? 0);
            }

            // kalau kurang 24 jam, kira ikut jam
            return $hours * ($this->vehicle->price_per_hour ?? 0);
        }
        return 0;
    }
    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'bookingID', 'bookingID');
    }

    public function hasReturnInspection(): bool
    {
        return $this->inspections()
            ->where('inspectionType', 'return')
            ->exists();
    }

    public function shouldAutoComplete(): bool
    {
        return
            $this->bookingStatus !== 'completed' &&
            $this->bookingStatus !== 'cancelled' &&
            Carbon::now()->greaterThan(Carbon::parse($this->return_dateTime)) &&
            $this->hasReturnInspection();
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'bookingID', 'bookingID');
    }

}
