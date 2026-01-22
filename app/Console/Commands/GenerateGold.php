<?php

namespace App\Console\Commands;

use App\Models\Kingdom;
use App\Models\GameConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateGold extends Command
{
    protected $signature = 'game:generate-gold';
    protected $description = 'Generate gold for all kingdoms based on their buildings';

    public function handle()
    {
        $this->info('Starting gold generation...');

        $defaultGold = GameConfig::getDefaultGoldPerMinute();
        $mineProduction = GameConfig::getGoldMineProduction();

        $kingdoms = Kingdom::with('kingdomBuildings.building')->get();
        $totalKingdoms = $kingdoms->count();
        $processed = 0;

        foreach ($kingdoms as $kingdom) {
            DB::transaction(function() use ($kingdom, $defaultGold, $mineProduction) {
                // Default gold
                $goldToAdd = $defaultGold;

                // Gold from mines
                $mines = $kingdom->kingdomBuildings()
                    ->whereHas('building', function($q) {
                        $q->where('type', 'mine')->where('is_active', true);
                    })
                    ->get();

                foreach ($mines as $mine) {
                    $goldToAdd += $mineProduction * $mine->quantity;
                }

                // Update kingdom gold
                $kingdom->increment('gold', $goldToAdd);
                $kingdom->update(['last_resource_update' => now()]);

                $this->line("Kingdom {$kingdom->name}: +{$goldToAdd} gold (Total: {$kingdom->gold})");
            });

            $processed++;
        }

        $this->info("Gold generation completed for {$processed}/{$totalKingdoms} kingdoms.");
        return 0;
    }
}
