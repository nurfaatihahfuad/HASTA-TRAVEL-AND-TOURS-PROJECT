<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class DamageCase extends Model
{
    protected $table = 'damage_case';             // nama table
    protected $primaryKey = 'caseID';             // PK
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;                   // kalau table tiada created_at/updated_at

    protected $fillable = [
        'casetype',
        'filledby',
        'resolutionstatus',
        'inspectionID', // FK ke inspection
    ];

    // Relationship: satu damage case belong kepada satu inspection
    public function inspection()
    {
        return $this->belongsTo(Inspection::class, 'inspectionID', 'inspectionID');
    }
}