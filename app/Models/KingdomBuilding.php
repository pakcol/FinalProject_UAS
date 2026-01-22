<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KingdomBuilding extends Model
{
    use HasFactory;

    protected $fillable = [
        'kingdom_id',
        'building_id',
        'quantity',
        'level',
        'last_production_at',
    ];

    protected $casts = [
        'last_production_at' => 'datetime',
    ];

    /**
     * Get the kingdom that owns this building
     */
    public function kingdom()
    {
        return $this->belongsTo(Kingdom::class);
    }

    /**
     * Get the building details
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get total production from this building
     */
    public function getTotalGoldProductionAttribute()
    {
        return $this->building->gold_production * $this->quantity;
    }

    /**
     * Get total troop production from this building
     */
    public function getTotalTroopProductionAttribute()
    {
        return $this->building->troop_production * $this->quantity;
    }

    /**
     * Get total defense bonus from this building
     */
    public function getTotalDefenseBonusAttribute()
    {
        return $this->building->defense_bonus * $this->quantity;
    }
}
