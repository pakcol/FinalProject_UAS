<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TribeSeeder extends Seeder
{
    public function run()
    {
        $tribes = [
            [
                'name' => 'Marksman',
                'description' => 'Suku yang mempunyai kelebihan di kekuatan serangan jarak jauh, namun kekurangan di pertahanan.',
                'melee_attack' => 30,
                'range_attack' => 90,
                'magic_attack' => 40,
                'melee_defense' => 40,
                'range_defense' => 60,
                'magic_defense' => 30,
                'troop_production_rate' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tank',
                'description' => 'Suku mempunyai kelebihan di kekuatan pertahanan, namun sangat lemah di kekuatan serang.',
                'melee_attack' => 40,
                'range_attack' => 20,
                'magic_attack' => 30,
                'melee_defense' => 90,
                'range_defense' => 80,
                'magic_defense' => 70,
                'troop_production_rate' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mage',
                'description' => 'Suku sangat kuat di serangan magic, namun sangat lemah di pertahanan.',
                'melee_attack' => 20,
                'range_attack' => 50,
                'magic_attack' => 95,
                'melee_defense' => 25,
                'range_defense' => 35,
                'magic_defense' => 45,
                'troop_production_rate' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Warrior',
                'description' => 'Suku sangat kuat di serangan jarak dekat, cukup kuat di pertahanan, namun sangat lemah di pertahanan terhadap magic dan serangan jarak jauh.',
                'melee_attack' => 85,
                'range_attack' => 35,
                'magic_attack' => 25,
                'melee_defense' => 70,
                'range_defense' => 40,
                'magic_defense' => 30,
                'troop_production_rate' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tribes')->insert($tribes);
    }
}