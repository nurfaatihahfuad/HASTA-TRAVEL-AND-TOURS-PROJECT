<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlacklistedCust extends Model
{
    protected $table = 'blacklistedcust';   
    protected $primaryKey = 'blacklistID';  
    public $timestamps = false;
    public $incrementing = true; //dina tukar true asal,false
    protected $keyType = 'int';               

    protected $fillable = [
        //'blacklistID', -tak incelude sbb auto increment
        'customerID',
        'reason',
        'adminID',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class, 'customerID', 'userID');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'adminID', 'adminID');
    }
}