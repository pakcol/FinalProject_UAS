<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'username'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function kingdom()
    {
        return $this->hasOne(Kingdom::class);
    }

    public function battlesAsAttacker()
    {
        return $this->hasManyThrough(Battle::class, Kingdom::class, 'user_id', 'attacker_id');
    }

    public function battlesAsDefender()
    {
        return $this->hasManyThrough(Battle::class, Kingdom::class, 'user_id', 'defender_id');
    }
}