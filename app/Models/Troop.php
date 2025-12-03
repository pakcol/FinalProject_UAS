<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Troop extends Model
{
    use HasFactory;

    protected $fillable = [
        'kingdom_id', 'quantity', 'melee_attack', 'range_attack',
        'magic_attack', 'melee_defense', 'range_defense', 'magic_defense',
        'last_production_update'
    ];

    protected $casts = [
        'last_production_update' => 'datetime'
    ];

    public function kingdom()
    {
        return $this->belongsTo(Kingdom::class);
    }
}