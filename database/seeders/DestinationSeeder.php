<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Destination;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $destinations = [
           ['name' => 'Europe', 'code' => 'EUR'],
           ['name' => 'North America', 'code' => 'USA'],
           ['name' => 'Canada', 'code' => 'CAN'],
           ['name' => 'Japan', 'code' => 'JPN'],
           ['name' => 'General / Export', 'code' => 'GEN'],
           ['name' => 'Middle East / GCC', 'code' => 'GCC'],
           ['name' => 'Asia / Pacific', 'code' => 'APC'],
           ['name' => 'China', 'code' => 'CHN'],
           ['name' => 'South Korea', 'code' => 'KOR'],
           ['name' => 'Latin America', 'code' => 'LATAM'],
           ['name' => 'Mexico', 'code' => 'MEX'],
           ['name' => 'Australia / Oceania', 'code' => 'AUS'],
           ['name' => 'South Africa', 'code' => 'ZAF'],
           ['name' => 'India', 'code' => 'IND'],
           ['name' => 'Southeast Asia', 'code' => 'SEA'],
        ];

        foreach ($destinations as $dest) {
            Destination::updateOrCreate(
                ['code' => $dest['code']], // Unique identifier
                ['name' => $dest['name']]
            );
        }
    }
}
