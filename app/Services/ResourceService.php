<?php

namespace App\Services;

use App\Models\Kingdom;

class ResourceService
{
    public function updateKingdomResources(Kingdom $kingdom)
    {
        $now = now();
        $lastUpdate = $kingdom->last_resource_update;
        $minutesPassed = $lastUpdate->diffInMinutes($now);

        if ($minutesPassed > 0) {
            // Calculate gold production
            $baseGoldPerMinute = 5;
            $mineGoldPerMinute = $kingdom->mines_count * 10;
            $totalGoldPerMinute = $baseGoldPerMinute + $mineGoldPerMinute;
            $goldEarned = $totalGoldPerMinute * $minutesPassed;

            // Calculate troop production
            $baseTroopsPerMinute = $kingdom->tribe->troop_production_rate;
            $barracksTroopsPerMinute = $kingdom->barracks_count * 5;
            $totalTroopsPerMinute = $baseTroopsPerMinute + $barracksTroopsPerMinute;
            $troopsProduced = $totalTroopsPerMinute * $minutesPassed;

            // Update kingdom
            $kingdom->update([
                'gold' => $kingdom->gold + $goldEarned,
                'total_troops' => $kingdom->total_troops + $troopsProduced,
                'last_resource_update' => $now
            ]);

            // Update troops
            $kingdom->troops->update([
                'quantity' => $kingdom->troops->quantity + $troopsProduced,
                'last_production_update' => $now
            ]);

            // Recalculate attack and defense power
            $kingdom->update([
                'total_attack_power' => $kingdom->calculateTotalAttackPower(),
                'total_defense_power' => $kingdom->calculateTotalDefensePower()
            ]);
        }
    }
}