<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $table = 'inspection';              // nama table
    protected $primaryKey = 'inspectionID';       // PK
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;                   // kalau table tiada created_at/updated_at

    protected $fillable = [
        'carCondition',
        'mileageReturned',
        'fuelLevel',
        'damageDetected',
        'remark',
        'evidence',
        'vehicleID',
        'staffID',
    ];

    // Relationship: inspection belongs to vehicle
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicleID', 'vehicleID');
    }

    // Relationship: inspection belongs to staff (user)
    public function staff()
    {
        // tukar 'id' kepada 'userID' kalau table user PK ialah userID
        return $this->belongsTo(User::class, 'staffID', 'userID');
    }

    // Relationship: inspection has one damage case
    public function damageCase()
    {
        return $this->hasOne(DamageCase::class, 'inspectionID', 'inspectionID');
    }

    // Accessor untuk format ID (contoh: IN001)
    public function getFormattedIdAttribute()
    {
        return 'IN' . str_pad($this->inspectionID, 3, '0', STR_PAD_LEFT);
    }

     // 🚀 Auto create damage case bila damageDetected = 'Yes'
    protected static function booted()
    {
        static::created(function ($inspection) {
            if ($inspection->damageDetected === 'Yes') {
                DamageCase::create([
                    'inspectionID'     => $inspection->inspectionID,
                    'casetype'         => 'Pending', // default, boleh tukar ikut logic awak
                    'filledby'         => $inspection->staff?->name ?? 'System',
                    'resolutionstatus' => 'Unresolved',
                ]);
            }
        });

        static::updated(function ($inspection) {
            if ($inspection->damageDetected === 'Yes') {
                // check kalau belum ada damage case untuk inspection ni
                if (!$inspection->damageCase) {
                    DamageCase::create([
                        'inspectionID'     => $inspection->inspectionID,
                        'casetype'         => 'Pending',
                        'filledby'         => $inspection->staff?->name ?? 'System',
                        'resolutionstatus' => 'Unresolved',
                    ]);
                }
            }
        });
    }
}
