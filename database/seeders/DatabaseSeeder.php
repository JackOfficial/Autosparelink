<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        BrandsTableSeeder::class,
        BodyTypeSeeder::class,
        EngineTypeSeeder::class,
        TransmissionTypesTableSeeder::class,
        DriveTypesSeeder::class,
        VehicleModelSeeder::class,
        VariantsSeeder::class,
        ]);
    }
}
