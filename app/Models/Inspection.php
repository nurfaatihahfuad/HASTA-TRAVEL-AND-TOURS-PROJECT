<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inspection extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'inspection';              // nama table
    protected $primaryKey = 'inspectionID';       // PK
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;                   // kalau table tiada created_at/updated_at

    protected $fillable = [
        'bookingID',
        'vehicleID', 
        'staffID', 
        'carCondition',
        'mileageReturned', 
        'fuelLevel', 
        'damageDetected',
        'remark', 
        'evidence',
        'fuel_evidence',     // tambah berdasarkan screenshot table
        'front_view',        // tambah berdasarkan screenshot table  
        'back_view',         // tambah berdasarkan screenshot table
        'right_view',        // tambah berdasarkan screenshot table
        'left_view',         // tambah berdasarkan screenshot table
        'inspectionType',    // tambah berdasarkan screenshot table (ENUM: pickup/return)
        'created_at', 
        'updated_at'          // include timestamps
    ];

    // Cast boolean fields
    protected $casts = [
        'damageDetected' => 'boolean',
        'carCondition' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========== RELATIONSHIPS ==========
    
    /**
     * Relationship: inspection belongs to vehicle
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicleID', 'vehicleID');
    }

    /**
     * Relationship: inspection belongs to staff (user)
     */
    public function staffUser(): BelongsTo
    {
        // tukar 'id' kepada 'userID' kalau table user PK ialah userID
        return $this->belongsTo(User::class, 'staffID', 'userID');
    }

    /**
     * Relationship: inspection belongs to booking
     */
    public function booking(): BelongsTo
    {
        // Parameter: Model Tujuan, Foreign Key di table inspection, Primary Key di table bookings
        return $this->belongsTo(Booking::class, 'bookingID', 'bookingID');
    }

    /**
     * Relationship: inspection has one damage case
     */
    public function damageCase()
    {
        return $this->hasOne(DamageCase::class, 'inspectionID', 'inspectionID');
    }

    // ========== SCOPES (For Dashboard Queries) ==========
    
    /**
     * Scope: Pending inspections (without remarks)
     */
    public function scopePending($query)
    {
        return $query->whereNull('remark');
    }

    /**
     * Scope: Today's inspections
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope: Pickup inspections
     */
    public function scopePickup($query)
    {
        return $query->where('inspectionType', 'pickup');
    }

    /**
     * Scope: Return inspections
     */
    public function scopeReturn($query)
    {
        return $query->where('inspectionType', 'return');
    }

    /**
     * Scope: Inspections with damage detected
     */
    public function scopeWithDamage($query)
    {
        return $query->where('damageDetected', true);
    }

    /**
     * Scope: Inspections without damage
     */
    public function scopeWithoutDamage($query)
    {
        return $query->where('damageDetected', false);
    }

    /**
     * Scope: Recent inspections (last 7 days)
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ========== ACCESSORS & MUTATORS ==========
    
    /**
     * Accessor untuk format ID (contoh: IN001)
     */
    public function getFormattedIdAttribute()
    {
        return 'IN' . str_pad($this->inspectionID, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Accessor untuk inspection type dengan label warna
     */
    public function getInspectionTypeLabelAttribute()
    {
        return match($this->inspectionType) {
            'pickup' => '<span style="color: #1976d2; font-weight: bold;">Pickup</span>',
            'return' => '<span style="color: #7b1fa2; font-weight: bold;">Return</span>',
            default => ucfirst($this->inspectionType)
        };
    }

    /**
     * Accessor untuk damage status dengan label warna
     */
    public function getDamageStatusAttribute()
    {
        if ($this->damageDetected) {
            return '<span style="color: #c62828; font-weight: bold;">Damage Detected</span>';
        }
        return '<span style="color: #2e7d32; font-weight: bold;">No Damage</span>';
    }

    /**
     * Accessor untuk status inspection (pending/complete)
     */
    public function getStatusAttribute()
    {
        return empty($this->remark) ? 'Pending' : 'Completed';
    }

    /**
     * Accessor untuk status inspection dengan warna
     */
    public function getStatusLabelAttribute()
    {
        if (empty($this->remark)) {
            return '<span style="color: #f57c00; font-weight: bold;">Pending</span>';
        }
        return '<span style="color: #388e3c; font-weight: bold;">Completed</span>';
    }

    // ========== BOOT METHOD ==========
    
    /**
     * 🚀 Auto create damage case bila damageDetected = true
     */
    protected static function booted()
    {
        static::created(function ($inspection) {
            if ($inspection->damageDetected === true) {
                DamageCase::create([
                    'inspectionID'     => $inspection->inspectionID,
                    'casetype'         => 'Pending', // default, boleh tukar ikut logic awak
                    'filledby'         => $inspection->staffUser?->name ?? 'System',
                    'resolutionstatus' => 'Unresolved',
                ]);
            }
        });

        static::updated(function ($inspection) {
            if ($inspection->damageDetected === true) {
                // check kalau belum ada damage case untuk inspection ni
                if (!$inspection->damageCase) {
                    DamageCase::create([
                        'inspectionID'     => $inspection->inspectionID,
                        'casetype'         => 'Pending',
                        'filledby'         => $inspection->staffUser?->name ?? 'System',
                        'resolutionstatus' => 'Unresolved',
                    ]);
                }
            }
        });
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Check if inspection has all evidence photos
     */
    public function hasCompleteEvidence(): bool
    {
        return !empty($this->front_view) && 
               !empty($this->back_view) && 
               !empty($this->right_view) && 
               !empty($this->left_view);
    }

    /**
     * Check if inspection has fuel evidence
     */
    public function hasFuelEvidence(): bool
    {
        return !empty($this->fuel_evidence);
    }

    /**
     * Get inspection summary for dashboard
     */
    public function getSummaryAttribute(): string
    {
        $summary = "Inspection #{$this->inspectionID} ({$this->inspectionType})";
        
        if ($this->vehicle) {
            $summary .= " - Vehicle #{$this->vehicleID}";
        }
        
        if ($this->damageDetected) {
            $summary .= " - ⚠️ Damage Detected";
        }
        
        return $summary;
    }

    /**
     * Get inspection date in human readable format
     */
    public function getInspectionDateAttribute(): string
    {
        return $this->created_at->format('d M Y, h:i A');
    }

    /**
     * Get car condition with emoji
     */
    public function getCarConditionWithEmojiAttribute(): string
    {
        return match(strtolower($this->carCondition)) {
            'excellent', 'baik' => '👍 ' . $this->carCondition,
            'good', 'baik' => '👌 ' . $this->carCondition,
            'fair', 'sederhana' => '⚠️ ' . $this->carCondition,
            'poor', 'teruk' => '❌ ' . $this->carCondition,
            default => $this->carCondition
        };
    }
}