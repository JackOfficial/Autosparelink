<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $variants = [
            // Hyundai Elantra (vehicle_model_id = 1)
            [
                'vehicle_model_id' => 1,
                'body_type_id' => 1, // Sedan
                'engine_type_id' => 1, // 2.0L Gasoline
                'transmission_type_id' => 1, // Automatic
                'fuel_capacity' => '50L',
                'seats' => 5,
                'doors' => 4,
                'drive_type' => 'FWD',
                'horsepower' => '147hp',
                'torque' => '132 lb-ft',
                'fuel_efficiency' => '14 km/l',
                'photo' => 'variants/elantra_1.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vehicle_model_id' => 1,
                'body_type_id' => 1,
                'engine_type_id' => 2, // 1.6L Turbo
                'transmission_type_id' => 2, // Manual
                'fuel_capacity' => '50L',
                'seats' => 5,
                'doors' => 4,
                'drive_type' => 'FWD',
                'horsepower' => '201hp',
                'torque' => '195 lb-ft',
                'fuel_efficiency' => '13 km/l',
                'photo' => 'variants/elantra_2.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Hyundai Tucson (vehicle_model_id = 2)
            [
                'vehicle_model_id' => 2,
                'body_type_id' => 2, // SUV
                'engine_type_id' => 3, // 2.0L Gasoline
                'transmission_type_id' => 1,
                'fuel_capacity' => '62L',
                'seats' => 5,
                'doors' => 5,
                'drive_type' => 'AWD',
                'horsepower' => '161hp',
                'torque' => '150 lb-ft',
                'fuel_efficiency' => '12 km/l',
                'photo' => 'variants/tucson_1.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Hyundai Santa Fe (vehicle_model_id = 3)
            [
                'vehicle_model_id' => 3,
                'body_type_id' => 2, // SUV
                'engine_type_id' => 4, // 2.4L Gasoline
                'transmission_type_id' => 1,
                'fuel_capacity' => '70L',
                'seats' => 7,
                'doors' => 5,
                'drive_type' => 'AWD',
                'horsepower' => '185hp',
                'torque' => '178 lb-ft',
                'fuel_efficiency' => '11 km/l',
                'photo' => 'variants/santafe_1.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // KIA Sportage (vehicle_model_id = 4)
            [
                'vehicle_model_id' => 4,
                'body_type_id' => 2, // SUV
                'engine_type_id' => 5, // 2.0L Gasoline
                'transmission_type_id' => 1,
                'fuel_capacity' => '62L',
                'seats' => 5,
                'doors' => 5,
                'drive_type' => 'FWD',
                'horsepower' => '181hp',
                'torque' => '175 lb-ft',
                'fuel_efficiency' => '12 km/l',
                'photo' => 'variants/sportage_1.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // KIA Sorento (vehicle_model_id = 5)
            [
                'vehicle_model_id' => 5,
                'body_type_id' => 2,
                'engine_type_id' => 5, // 2.5L Gasoline
                'transmission_type_id' => 1,
                'fuel_capacity' => '71L',
                'seats' => 7,
                'doors' => 5,
                'drive_type' => 'AWD',
                'horsepower' => '191hp',
                'torque' => '182 lb-ft',
                'fuel_efficiency' => '11 km/l',
                'photo' => 'variants/sorento_1.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Toyota Corolla (vehicle_model_id = 6)
            [
                'vehicle_model_id' => 6,
                'body_type_id' => 1, // Sedan
                'engine_type_id' => 4, // 1.8L Gasoline
                'transmission_type_id' => 1,
                'fuel_capacity' => '50L',
                'seats' => 5,
                'doors' => 4,
                'drive_type' => 'FWD',
                'horsepower' => '139hp',
                'torque' => '126 lb-ft',
                'fuel_efficiency' => '14 km/l',
                'photo' => 'variants/corolla_1.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Toyota Camry (vehicle_model_id = 8)
            [
                'vehicle_model_id' => 2,
                'body_type_id' => 1,
                'engine_type_id' => 2, // 2.5L Gasoline
                'transmission_type_id' => 1,
                'fuel_capacity' => '60L',
                'seats' => 5,
                'doors' => 4,
                'drive_type' => 'FWD',
                'horsepower' => '203hp',
                'torque' => '184 lb-ft',
                'fuel_efficiency' => '13 km/l',
                'photo' => 'variants/camry_1.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // BMW 3 Series (vehicle_model_id = 13)
            [
                'vehicle_model_id' => 2,
                'body_type_id' => 1, // Sedan
                'engine_type_id' => 3, // 2.0L Gasoline
                'transmission_type_id' => 1,
                'fuel_capacity' => '60L',
                'seats' => 5,
                'doors' => 4,
                'drive_type' => 'RWD',
                'horsepower' => '255hp',
                'torque' => '295 lb-ft',
                'fuel_efficiency' => '12 km/l',
                'photo' => 'variants/3series_1.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // BMW X5 (vehicle_model_id = 14)
            [
                'vehicle_model_id' => 14,
                'body_type_id' => 2, // SUV
                'engine_type_id' => 3, // 3.0L Gasoline
                'transmission_type_id' => 1,
                'fuel_capacity' => '85L',
                'seats' => 5,
                'doors' => 5,
                'drive_type' => 'AWD',
                'horsepower' => '335hp',
                'torque' => '330 lb-ft',
                'fuel_efficiency' => '10 km/l',
                'photo' => 'variants/x5_1.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('variants')->insert($variants);
    }
}
