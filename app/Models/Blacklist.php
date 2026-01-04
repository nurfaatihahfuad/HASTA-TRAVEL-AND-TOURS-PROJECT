<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;


class Blacklist extends Model
{
    protected $table = 'blacklist';
    protected $primaryKey = 'blacklistID';
    public $timestamps = false;

    protected $fillable = [
        'customerUID', 'reason'
    ];
}
