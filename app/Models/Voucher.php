<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Voucher extends Model
{
    use HasFactory;

    protected $table = 'voucher';
    protected $primaryKey = 'voucherCode';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'voucherCode', 'type', 'expiryDate', 'limit', 'status'
    ];

    // Voucher belongs to many Customers
    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_voucher', 'voucherCode', 'customerID')
                    ->withPivot('redeemed_at');
    }
}