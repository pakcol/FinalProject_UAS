<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'username', 
        'tribe_id',
        'is_admin'
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    /**
     * Get the tribe selected by user
     */
    public function tribe()
    {
        return $this->belongsTo(Tribe::class);
    }

    /**
     * Get the kingdom owned by user
     */
    public function kingdom()
    {
        return $this->hasOne(Kingdom::class);
    }

    /**
     * Get battles as attacker
     */
    public function battlesAsAttacker()
    {
        return $this->hasManyThrough(Battle::class, Kingdom::class, 'user_id', 'attacker_id');
    }

    /**
     * Get battles as defender
     */
    public function battlesAsDefender()
    {
        return $this->hasManyThrough(Battle::class, Kingdom::class, 'user_id', 'defender_id');
    }
}
