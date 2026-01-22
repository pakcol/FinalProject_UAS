<?php

namespace App\Console\Commands;

use App\Models\Kingdom;
use App\Models\Troop;
use App\Models\GameConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProduceTroops extends Command
{
    protected $signature = 'game:produce-troops';
    protected $description = 'Produce troops for all kingdoms based on their barracks';

    public function handle()
    {
        $this->info('Starting troop production...');

        $troopPerBarracks = GameConfig::getBarracksTroopProduction();

        $kingdoms = Kingdom::with('kingdomBuildings.building', 'troops')->get();
        $totalKingdoms = $kingdoms->count();
        $processed = 0;

        foreach ($kingdoms as $kingdom) {
            DB::transaction(function() use ($kingdom, $troopPerBarracks) {
                // Count barracks
                $barracks = $kingdom->kingdomBuildings()
                    ->whereHas('building', function($q) {
                        $q->where('type', 'barracks')->where('is_active', true);
                    })
                    ->get();

                if ($barracks->isEmpty()) {
                    $this->line("Kingdom {$kingdom->name}: No barracks, skipping.");
                    return;
                }

                $totalBarracks = $barracks->sum('quantity');
                $troopsToAdd = $troopPerBarracks * $totalBarracks;

                // Get or create troop record
                $troop = $kingdom->troops;
                if (!$troop) {
                    $troop = Troop::create([
                        'kingdom_id' => $kingdom->id,
                        'quantity' => $troopsToAdd,
                        'last_production_update' => now(),
                    ]);
                } else {
                    $troop->increment('quantity', $troopsToAdd);
                    $troop->update(['last_production_update' => now()]);
                }

                // Update kingdom total troops
                $kingdom->update([
                    'total_troops' => $troop->quantity,
                ]);

                // Update power calculations
                $kingdom->updatePower();

                $this->line("Kingdom {$kingdom->name}: +{$troopsToAdd} troops from {$totalBarracks} barracks (Total: {$troop->quantity})");
            });

            $processed++;
        }

        $this->info("Troop production completed for {$processed}/{$totalKingdoms} kingdoms.");
        return 0;
    }
}
