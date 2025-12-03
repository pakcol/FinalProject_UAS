<?php

namespace App\Http\Controllers;

use App\Models\Kingdom;
use App\Models\Battle;
use App\Models\Tribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BattleController extends Controller
{
    public function showBattle()
    {
        $user = Auth::user();
        $userKingdom = $user->kingdom;

        // Battle history
        $battleHistory = Battle::where('attacker_id', $userKingdom->id)
            ->orWhere('defender_id', $userKingdom->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Target kingdom asli
        $targetKingdoms = Kingdom::with('user', 'tribe')
            ->where('id', '!=', $userKingdom->id)
            ->where('total_troops', '>', 0)
            ->get();

        // Jika tidak ada lawan asli → gunakan AI
        if ($targetKingdoms->count() === 0) {
            $targetKingdoms = $this->generateAiTargets(5, $userKingdom);
        }

        return view('game.battle', compact('userKingdom', 'targetKingdoms', 'battleHistory'));
    }

    /**
     * Generate AI musuh
     */
    protected function generateAiTargets(int $count = 5, Kingdom $userKingdom = null)
    {
        $tribes = Tribe::inRandomOrder()->limit($count)->get();

        while ($tribes->count() < $count) {
            $more = Tribe::inRandomOrder()->limit($count - $tribes->count())->get();
            $tribes = $tribes->concat($more);
        }

        $aiTargets = collect();

        for ($i = 0; $i < $count; $i++) {
            $tribe = $tribes[$i];

            $userTroops = $userKingdom ? (int) $userKingdom->total_troops : 20;

            // Troop & defense AI
            $baseTroops = max(10, (int) round($userTroops * (0.6 + (mt_rand(0, 40) / 100))));
            $baseDefense = (int) round(
                ($tribe->melee_defense + $tribe->range_defense + $tribe->magic_defense) * ($baseTroops / 100)
            );

            $ai = new \stdClass();
            $ai->id = 100000 + $i; // AI ID start at 100000
            $ai->name = ucfirst($tribe->name) . ' Outpost ' . ($i + 1);
            $ai->total_troops = $baseTroops;
            $ai->total_defense_power = $baseDefense + mt_rand(0, 30);
            $ai->tribe = $tribe;

            // Fake user
            $fakeUser = new \stdClass();
            $fakeUser->username = 'NPC_' . Str::upper(Str::random(3)) . ($i + 1);
            $ai->user = $fakeUser;

            $aiTargets->push($ai);
        }

        return $aiTargets;
    }

    /**
     * Fungsi utama serangan
     */
    public function attack(Request $request)
    {
        $request->validate([
            'defender_id' => 'required'
        ]);

        $user = Auth::user();
        $attackerKingdom = $user->kingdom;
        $defenderId = $request->input('defender_id');

        // ============================================================
        //                     SERANGAN TERHADAP AI
        // ============================================================
        if ($defenderId >= 100000) {

            $aiTargets = $this->generateAiTargets(10, $attackerKingdom);
            $defender = $aiTargets->firstWhere('id', $defenderId);

            if (!$defender) {
                return redirect()->back()->with('error', 'Target tidak ditemukan.');
            }

            return $this->resolveBattle(
                $attackerKingdom,
                null,                   // defender = NULL (AI)
                $defender->name,
                $defender->total_troops,
                $defender->total_defense_power
            );
        }

        // ============================================================
        //                SERANGAN KE KERAJAAN ASLI
        // ============================================================
        $defender = Kingdom::find($defenderId);

        if (!$defender) {
            return redirect()->back()->with('error', 'Target tidak ditemukan.');
        }

        return $this->resolveBattle(
            $attackerKingdom,
            $defender,
            $defender->name,
            $defender->total_troops,
            $defender->total_defense_power
        );
    }

    /**
     * Penyelesaian pertempuran (AI atau Player)
     */
    protected function resolveBattle(
        Kingdom $attacker,
        $defenderModel,         // null jika AI
        string $defenderName,
        int $defTroops,
        int $defPower
    ) {
        $attPower = $attacker->total_attack_power + mt_rand(-10, 10);
        $defPower = $defPower + mt_rand(-10, 10);

        $attTroops = (int) $attacker->total_troops;
        $defTroops = (int) $defTroops;

        $isWin = $attPower >= $defPower;

        if ($isWin) {

            $goldStolen = rand(10, 100);
            $attacker->gold += $goldStolen;
            $attacker->save();

            Battle::create([
                'attacker_id' => $attacker->id,
                'defender_id' => $defenderModel->id ?? null,
                'result' => 'win',
                'gold_stolen' => $goldStolen,
                'attacker_troops' => $attTroops,
                'defender_troops' => $defTroops,
                'attacker_power' => $attPower,
                'defender_power' => $defPower,
                'battle_log' => "You attacked {$defenderName} and looted {$goldStolen} gold."
            ]);

            return redirect()->route('game.battle')->with('battle_result', [
                'result' => 'win',
                'gold_stolen' => $goldStolen,
                'log' => "You attacked {$defenderName} and looted {$goldStolen} gold."
            ]);
        }

        // When lose
        Battle::create([
            'attacker_id' => $attacker->id,
            'defender_id' => $defenderModel->id ?? null,
            'result' => 'lose',
            'gold_stolen' => 0,
            'attacker_troops' => $attTroops,
            'defender_troops' => $defTroops,
            'attacker_power' => $attPower,
            'defender_power' => $defPower,
            'battle_log' => "Your attack on {$defenderName} failed."
        ]);

        return redirect()->route('game.battle')->with('battle_result', [
            'result' => 'lose',
            'gold_stolen' => 0,
            'log' => "Your attack on {$defenderName} failed."
        ]);
    }
}
