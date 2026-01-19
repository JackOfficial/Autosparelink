<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $vehicleModels = [
             // Hyundai
        [
            'brand_id' => 1,
            'model_name' => 'Elantra',
            'description' => 'Compact car with modern design and efficient performance.',
            'production_start_year' => 1990,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 1,
            'model_name' => 'Tucson',
            'description' => 'Compact SUV with advanced safety and tech features.',
            'production_start_year' => 2004,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 1,
            'model_name' => 'Santa Fe',
            'description' => 'Mid-size SUV with spacious interior and reliable performance.',
            'production_start_year' => 2000,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],

        // KIA
        [
            'brand_id' => 2,
            'model_name' => 'Sportage',
            'description' => 'Compact crossover SUV with sporty design.',
            'production_start_year' => 1993,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 2,
            'model_name' => 'Sorento',
            'description' => 'Mid-size SUV with flexible seating and tech features.',
            'production_start_year' => 2002,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 2,
            'model_name' => 'Carnival',
            'description' => 'Large MPV for family comfort and practicality.',
            'production_start_year' => 1998,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],

        // Toyota
        [
            'brand_id' => 3,
            'model_name' => 'Corolla',
            'description' => 'Reliable compact sedan, one of the best-selling cars in the world.',
            'production_start_year' => 1966,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 3,
            'model_name' => 'RAV4',
            'description' => 'Compact SUV with great fuel efficiency and safety features.',
            'production_start_year' => 1994,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 3,
            'model_name' => 'Camry',
            'description' => 'Mid-size sedan known for comfort and reliability.',
            'production_start_year' => 1982,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],

        // Mercedes-Benz
        [
            'brand_id' => 3,
            'model_name' => 'C-Class',
            'description' => 'Compact executive car with luxury features.',
            'production_start_year' => 1993,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 3,
            'model_name' => 'GLE',
            'description' => 'Mid-size luxury SUV with premium amenities.',
            'production_start_year' => 1997,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 4,
            'model_name' => 'S-Class',
            'description' => 'Flagship luxury sedan with top-of-the-line technology.',
            'production_start_year' => 1972,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],

        // BMW
        [
            'brand_id' => 4,
            'model_name' => '3 Series',
            'description' => 'Compact luxury sedan with sporty performance.',
            'production_start_year' => 1975,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 4,
            'model_name' => 'X5',
            'description' => 'Mid-size luxury SUV with advanced tech and comfort.',
            'production_start_year' => 1999,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 4,
            'model_name' => '5 Series',
            'description' => 'Executive sedan with a balance of luxury and performance.',
            'production_start_year' => 1972,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],

        // Volkswagen
        [
            'brand_id' => 3,
            'model_name' => 'Golf',
            'description' => 'Compact car, one of the most popular VW models.',
            'production_start_year' => 1974,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 3,
            'model_name' => 'Passat',
            'description' => 'Mid-size sedan with German engineering and comfort.',
            'production_start_year' => 1973,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],

        // BYD
        [
            'brand_id' => 4,
            'model_name' => 'Han',
            'description' => 'Electric sedan with long range and modern design.',
            'production_start_year' => 2020,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 4,
            'model_name' => 'Tang',
            'description' => 'Electric SUV with spacious interior and strong performance.',
            'production_start_year' => 2015,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],

        // Honda
        [
            'brand_id' => 2,
            'model_name' => 'Civic',
            'description' => 'Compact car known for reliability and fuel efficiency.',
            'production_start_year' => 1972,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 2,
            'model_name' => 'CR-V',
            'description' => 'Compact SUV with practicality and reliability.',
            'production_start_year' => 1995,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],

        // Mitsubishi
        [
            'brand_id' => 3,
            'model_name' => 'Lancer',
            'description' => 'Compact car known for performance and affordability.',
            'production_start_year' => 1973,
            'production_end_year' => 2017,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 3,
            'model_name' => 'Outlander',
            'description' => 'Mid-size SUV with family-friendly features.',
            'production_start_year' => 2001,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],

        // Nissan
        [
            'brand_id' => 5,
            'model_name' => 'Altima',
            'description' => 'Mid-size sedan with comfort and efficiency.',
            'production_start_year' => 1992,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'brand_id' => 5,
            'model_name' => 'Rogue',
            'description' => 'Compact SUV with safety and technology features.',
            'production_start_year' => 2007,
            'production_end_year' => null,
            'has_variants' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
            
            // You can continue adding more models for other brands...
        ];

        DB::table('vehicle_models')->insert($vehicleModels);
    }
}
