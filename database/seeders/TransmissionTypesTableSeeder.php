<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransmissionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $transmissions = [
            [
                'name' => 'Manual',
                'gears_count' => '5-6',
                'description' => 'Traditional manual gearbox operated by a clutch pedal.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Automatic',
                'gears_count' => '6-10',
                'description' => 'Fully automatic transmission with torque converter.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CVT',
                'gears_count' => 'N/A',
                'description' => 'Continuously Variable Transmission offering smooth gear transitions.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dual-Clutch (DCT)',
                'gears_count' => '6-8',
                'description' => 'Dual-clutch automated gearbox providing fast and smooth shifting.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tiptronic',
                'gears_count' => '6-8',
                'description' => 'Automatic gearbox allowing manual gear selection.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AMT (Automated Manual)',
                'gears_count' => '5-6',
                'description' => 'Automated manual transmission commonly found in budget cars.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('transmission_types')->insert($transmissions);
    }
}
