<?php

namespace App\Services;

use App\Models\Kingdom;
use App\Models\Building;

class BuildingService
{
    public function buildBarracks(Kingdom $kingdom, Building $building)
    {
        $kingdom->update([
            'barracks_count' => $kingdom->barracks_count + 1,
            'gold' => $kingdom->gold - $building->gold_cost
        ]);
    }

    public function buildMine(Kingdom $kingdom, Building $building)
    {
        $kingdom->update([
            'mines_count' => $kingdom->mines_count + 1,
            'gold' => $kingdom->gold - $building->gold_cost
        ]);
    }

    public function buildWalls(Kingdom $kingdom, Building $building)
    {
        $kingdom->update([
            'walls_count' => $kingdom->walls_count + 1,
            'gold' => $kingdom->gold - $building->gold_cost
        ]);
    }

    public function upgradeMainBuilding(Kingdom $kingdom, Building $building)
    {
        $upgradeCost = $building->gold_cost * $kingdom->main_building_level;
        
        $kingdom->update([
            'main_building_level' => $kingdom->main_building_level + 1,
            'gold' => $kingdom->gold - $upgradeCost
        ]);
    }
}