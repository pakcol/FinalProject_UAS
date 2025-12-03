<?php

namespace App\Services;

use App\Models\Battle;
use App\Models\Kingdom;

class BattleService
{
    public function conductBattle(Kingdom $attacker, Kingdom $defender)
    {
        $attackerPower = $attacker->calculateTotalAttackPower();
        $defenderPower = $defender->calculateTotalDefensePower();

        $battleLog = "Battle between {$attacker->name} and {$defender->name}\n";
        $battleLog .= "Attacker Power: {$attackerPower}\n";
        $battleLog .= "Defender Power: {$defenderPower}\n";

        if ($attackerPower > $defenderPower) {
            // Attacker wins
            $goldStolen = floor($defender->gold * 0.9);
            
            $attacker->update(['gold' => $attacker->gold + $goldStolen]);
            $defender->update(['gold' => $defender->gold - $goldStolen]);

            // Defender loses all troops
            $defender->troops->update(['quantity' => 0]);

            $battleLog .= "Result: Attacker wins!\n";
            $battleLog .= "Gold stolen: {$goldStolen}\n";
            $battleLog .= "Defender troops defeated!";

            $result = 'win';
        } else {
            // Defender wins
            $survivingDefenders = floor(($defenderPower - $attackerPower) / ($defender->troops->quantity ?: 1));
            
            $attacker->troops->update(['quantity' => 0]);
            $defender->troops->update(['quantity' => max(0, $survivingDefenders)]);

            $battleLog .= "Result: Defender wins!\n";
            $battleLog .= "Attacker troops defeated!\n";
            $battleLog .= "Defender surviving troops: {$survivingDefenders}";

            $result = 'lose';
            $goldStolen = 0;
        }

        // Create battle record
        $battle = Battle::create([
            'attacker_id' => $attacker->id,
            'defender_id' => $defender->id,
            'attacker_troops' => $attacker->troops->quantity,
            'defender_troops' => $defender->troops->quantity,
            'attacker_power' => $attackerPower,
            'defender_power' => $defenderPower,
            'gold_stolen' => $goldStolen,
            'result' => $result,
            'battle_log' => $battleLog
        ]);

        // Update kingdom stats
        $attacker->update([
            'total_attack_power' => $attacker->calculateTotalAttackPower(),
            'total_defense_power' => $attacker->calculateTotalDefensePower()
        ]);

        $defender->update([
            'total_attack_power' => $defender->calculateTotalAttackPower(),
            'total_defense_power' => $defender->calculateTotalDefensePower()
        ]);

        return [
            'result' => $result,
            'log' => $battleLog,
            'gold_stolen' => $goldStolen,
            'battle_id' => $battle->id
        ];
    }
}