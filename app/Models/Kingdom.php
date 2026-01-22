<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kingdom extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'tribe_id', 'name', 'gold', 'main_building_level',
        'barracks_count', 'mines_count', 'walls_count', 'total_troops',
        'total_attack_power', 'total_defense_power', 'last_resource_update'
    ];

    protected $casts = [
        'last_resource_update' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tribe()
    {
        return $this->belongsTo(Tribe::class);
    }

    public function troops()
    {
        return $this->hasOne(Troop::class);
    }

    public function attacks()
    {
        return $this->hasMany(Battle::class, 'attacker_id');
    }

    public function defenses()
    {
        return $this->hasMany(Battle::class, 'defender_id');
    }

    public function kingdomBuildings()
    {
        return $this->hasMany(KingdomBuilding::class);
    }

    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'kingdom_buildings')
                    ->withPivot('quantity', 'level')
                    ->withTimestamps();
    }

    /**
     * Get building by type
     */
    public function getBuilding($type)
    {
        return $this->kingdomBuildings()
            ->whereHas('building', function($q) use ($type) {
                $q->where('type', $type);
            })
            ->first();
    }

    /**
     * Check if kingdom has building
     */
    public function hasBuilding($type)
    {
        return $this->getBuilding($type) !== null;
    }

    /**
     * Get total gold production per minute
     */
    public function getTotalGoldProductionPerMinute()
    {
        $baseProduction = 5; // Default 5 gold per minute
        $mineProduction = $this->kingdomBuildings()
            ->whereHas('building', function($q) {
                $q->where('type', 'mine');
            })
            ->get()
            ->sum('total_gold_production');
        
        return $baseProduction + $mineProduction;
    }

    /**
     * Get total troop production per minute
     */
    public function getTotalTroopProductionPerMinute()
    {
        return $this->kingdomBuildings()
            ->whereHas('building', function($q) {
                $q->where('type', 'barracks');
            })
            ->get()
            ->sum('total_troop_production');
    }

    /**
     * Get total defense bonus from buildings
     */
    public function getTotalDefenseBonus()
    {
        return $this->kingdomBuildings()->get()->sum('total_defense_bonus');
    }

    /**
     * Check if kingdom can be attacked
     */
    public function canBeAttacked()
    {
        return $this->hasBuilding('barracks') && $this->hasBuilding('mine');
    }

    public function calculateTotalAttackPower()
    {
        $tribe = $this->tribe;
        $troops = $this->troops;
        
        if (!$troops) return 0;
        
        $melee_attack = ($tribe->melee_attack * $troops->quantity) / 100;
        $range_attack = ($tribe->range_attack * $troops->quantity) / 100;
        $magic_attack = ($tribe->magic_attack * $troops->quantity) / 100;
        
        return $melee_attack + $range_attack + $magic_attack;
    }

    public function calculateTotalDefensePower()
    {
        $tribe = $this->tribe;
        $troops = $this->troops;
        
        if (!$troops) return 0;
        
        $building_defense = $this->getTotalDefenseBonus();
        
        $melee_defense = ($tribe->melee_defense * $troops->quantity) / 100;
        $range_defense = ($tribe->range_defense * $troops->quantity) / 100;
        $magic_defense = ($tribe->magic_defense * $troops->quantity) / 100;
        
        return $melee_defense + $range_defense + $magic_defense + $building_defense;
    }

    public function updatePower()
    {
        $this->total_attack_power = $this->calculateTotalAttackPower();
        $this->total_defense_power = $this->calculateTotalDefensePower();
        $this->save();
    }
}
