<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $driveTypes = [
            [
                'name' => 'FWD',
                'description' => 'Front-Wheel Drive: the engine powers the front wheels only.',
            ],
            [
                'name' => 'RWD',
                'description' => 'Rear-Wheel Drive: the engine powers the rear wheels only.',
            ],
            [
                'name' => 'AWD',
                'description' => 'All-Wheel Drive: the engine powers all four wheels simultaneously for better traction.',
            ],
            [
                'name' => '4WD',
                'description' => 'Four-Wheel Drive: similar to AWD but often with selectable modes for off-road use.',
            ],
        ];

        DB::table('drive_types')->insert($driveTypes);
    }
}
