<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tribe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'melee_attack', 'range_attack', 'magic_attack',
        'melee_defense', 'range_defense', 'magic_defense', 'head_appearance',
        'body_appearance', 'legs_appearance', 'hands_appearance', 'troop_production_rate'
    ];

    public function kingdoms()
    {
        return $this->hasMany(Kingdom::class);
    }
}