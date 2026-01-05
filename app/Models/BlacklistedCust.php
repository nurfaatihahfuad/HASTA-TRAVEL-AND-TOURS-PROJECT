<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlacklistedCust extends Model
{
    protected $table = 'blacklistedcust';   // 👉 nama table sebenar
    protected $primaryKey = 'blacklistID';  // 👉 PK column
    public $timestamps = false;             // table anda tiada created_at/updated_at

    protected $fillable = [
        'customerID',
        'reason',
        'adminID',
    ];
}