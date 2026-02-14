<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EngineDisplacement;

class EngineDisplacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $displacements = [
            '0.8L', '1.0L', '1.2L', '1.4L', '1.6L', '1.8L',
            '2.0L', '2.2L', '2.5L', '3.0L', '3.5L', '4.0L', '5.0L'
        ];

        foreach ($displacements as $d) {
            EngineDisplacement::firstOrCreate(['name' => $d]);
        }
    }
}