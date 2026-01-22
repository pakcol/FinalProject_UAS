<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'type', 
        'description', 
        'gold_cost', 
        'level',
        'gold_production', 
        'troop_production', 
        'defense_bonus',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
