<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EngineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $engineTypes = [
            [
                'name' => 'Petrol (Gasoline)',
                'description' => 'A spark-ignition internal combustion engine powered by gasoline.',
                'icon_url' => 'icons/engines/petrol.png',
            ],
            [
                'name' => 'Diesel',
                'description' => 'A compression-ignition engine known for durability and fuel efficiency.',
                'icon_url' => 'icons/engines/diesel.png',
            ],
            [
                'name' => 'Hybrid',
                'description' => 'Combines an internal combustion engine with an electric motor for improved efficiency.',
                'icon_url' => 'icons/engines/hybrid.png',
            ],
            [
                'name' => 'Electric',
                'description' => 'Fully electric motor powered by rechargeable batteries, no combustion.',
                'icon_url' => 'icons/engines/electric.png',
            ],
            [
                'name' => 'Plug-In Hybrid (PHEV)',
                'description' => 'Hybrid vehicle with a larger battery that can be charged via external power.',
                'icon_url' => 'icons/engines/phev.png',
            ],
            [
                'name' => 'Hydrogen Fuel Cell',
                'description' => 'Uses hydrogen gas to produce electricity with water vapor as the only emission.',
                'icon_url' => 'icons/engines/hydrogen.png',
            ],
            [
                'name' => 'Turbocharged',
                'description' => 'Internal combustion engine enhanced by a turbocharger for higher performance.',
                'icon_url' => 'icons/engines/turbo.png',
            ],
            [
                'name' => 'Supercharged',
                'description' => 'Engine that uses a supercharger to increase air intake and boost power.',
                'icon_url' => 'icons/engines/supercharged.png',
            ],
        ];

        DB::table('engine_types')->insert($engineTypes);
    }
}
