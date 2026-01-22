<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    use HasFactory;

    protected $fillable = [
        'attacker_id',
        'defender_id',
        'attacker_troops',
        'defender_troops',
        'gold_stolen',
        'winner_id',
        'type'
    ];

    public function attacker()
    {
        return $this->belongsTo(Kingdom::class, 'attacker_id');
    }

    public function defender()
    {
        return $this->belongsTo(Kingdom::class, 'defender_id');
    }
    
    public function winner()
    {
        return $this->belongsTo(Kingdom::class, 'winner_id');
    }
}
