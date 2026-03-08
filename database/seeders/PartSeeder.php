<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Part;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $parts = [
            [
                'part_number' => 'BRK-001',
                'part_name' => 'Front Brake Pads',
                'category_id' => 1,
                'brand_id' => 1,
                'description' => 'High-performance ceramic brake pads for improved stopping power.',
                'price' => 45000,
                'stock_quantity' => 30,
                'status' => 1,
            ],
            [
                'part_number' => 'FLT-102',
                'part_name' => 'Engine Oil Filter',
                'category_id' => 2,
                'brand_id' => 2,
                'description' => 'Durable oil filter suitable for Toyota and Lexus engines.',
                'price' => 12000,
                'stock_quantity' => 80,
                'status' => 1,
            ],
            [
                'part_number' => 'SPK-550',
                'part_name' => 'Spark Plug Set (4 pcs)',
                'category_id' => 3,
                'brand_id' => 1,
                'description' => 'Copper core spark plugs ensuring efficient combustion.',
                'price' => 25000,
                'stock_quantity' => 50,
                'status' => 1,
            ],
            [
                'part_number' => 'AIR-210',
                'part_name' => 'Air Filter',
                'category_id' => 2,
                'brand_id' => 3,
                'description' => 'OEM-grade high-flow air filter for fuel efficiency.',
                'price' => 15000,
                'stock_quantity' => 60,
                'status' => 1,
            ],
            [
                'part_number' => 'SHK-700',
                'part_name' => 'Rear Shock Absorber',
                'category_id' => 4,
                'brand_id' => 1,
                'description' => 'Gas-charged shock absorber for smoother rides.',
                'price' => 85000,
                'stock_quantity' => 20,
                'status' => 1,
            ],
        ];

        foreach ($parts as $part) {
            Part::create($part);
        }
    }
}
