<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GameConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Get config value by key with caching
     */
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("game_config_{$key}", 3600, function () use ($key, $default) {
            $config = self::where('key', $key)->first();
            return $config ? $config->value : $default;
        });
    }

    /**
     * Set config value and clear cache
     */
    public static function setValue(string $key, $value, string $description = null)
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description ?? "Configuration for {$key}"
            ]
        );
        
        Cache::forget("game_config_{$key}");
    }

    /**
     * Get default gold per minute
     */
    public static function getDefaultGoldPerMinute()
    {
        return (int) self::getValue('default_gold_per_minute', 5);
    }

    /**
     * Get gold mine production per minute
     */
    public static function getGoldMineProduction()
    {
        return (int) self::getValue('gold_mine_production', 10);
    }

    /**
     * Get barracks troop production per minute
     */
    public static function getBarracksTroopProduction()
    {
        return (int) self::getValue('barracks_troop_production', 5);
    }

    /**
     * Get attack gold steal percentage
     */
    public static function getAttackGoldStealPercentage()
    {
        return (int) self::getValue('attack_gold_steal_percentage', 90);
    }
}
