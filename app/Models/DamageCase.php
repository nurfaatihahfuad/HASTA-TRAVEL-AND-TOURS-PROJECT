<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class DamageCase extends Model
{
    protected $table = 'damage_case';
    protected $primaryKey = 'caseid';
    public $timestamps = false;

    protected $fillable = [
        'casetype', 'filledby', 'resolutionstatus', 'inspectionid'
    ];
}
