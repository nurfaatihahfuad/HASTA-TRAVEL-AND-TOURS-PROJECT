<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DamageCase extends Model
{
    protected $table = 'damage_case';
    protected $primaryKey = 'caseID';
    public $incrementing = true;
    public $timestamps = false;

    // Gunakan columns yang sebenarnya ada
    protected $fillable = [
        'userID',           // Pastikan ini wujud
        'casetype',
        'severity',
        'damage_photos',
        'filledby',
        'resolutionstatus',
        'inspectionID'
    ];

    // Jika column userID memang tidak wujud, ganti dengan column lain
    // Contoh jika ada column 'created_by' atau 'staffID'
    
    // Relationships - adjust berdasarkan column yang betul
    public function inspection()
    {
        return $this->belongsTo(Inspection::class, 'inspectionID', 'inspectionID');
    }

    public function user()
    {
        // Jika column userID wujud
        return $this->belongsTo(User::class, 'userID', 'userID');
        
        // Jika column userID tidak wujud, mungkin ada column lain
        // return $this->belongsTo(User::class, 'created_by', 'userID');
    }

    // Helper untuk photos
    public function getPhotosArrayAttribute()
    {
        $photos = $this->damage_photos;
        if (empty($photos)) {
            return [];
        }
        
        try {
            return json_decode($photos, true) ?? [];
        } catch (\Exception $e) {
            return [$photos]; // Jika bukan JSON, return sebagai array
        }
    }
}