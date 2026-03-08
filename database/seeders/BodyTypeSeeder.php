<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BodyType;

class BodyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $bodyTypes = [
            [
                'name' => 'Sedan',
                'description' => 'A passenger car with a separate trunk and seating for four or more people.',
                'icon_url' => 'icons/sedan.png'
            ],
            [
                'name' => 'Hatchback',
                'description' => 'A compact vehicle with a rear door that swings upward to provide access to the cargo area.',
                'icon_url' => 'icons/hatchback.png'
            ],
            [
                'name' => 'SUV',
                'description' => 'Sport Utility Vehicle offering higher ground clearance and off-road capability.',
                'icon_url' => 'icons/suv.png'
            ],
            [
                'name' => 'Crossover',
                'description' => 'A blend of SUV styling and car-like comfort built on a unibody platform.',
                'icon_url' => 'icons/crossover.png'
            ],
            [
                'name' => 'Coupe',
                'description' => 'A stylish two-door car designed for sporty performance.',
                'icon_url' => 'icons/coupe.png'
            ],
            [
                'name' => 'Convertible',
                'description' => 'A car with a removable or foldable roof for open-air driving.',
                'icon_url' => 'icons/convertible.png'
            ],
            [
                'name' => 'Pickup Truck',
                'description' => 'A light-duty truck with an enclosed cab and open cargo bed.',
                'icon_url' => 'icons/pickup.png'
            ],
            [
                'name' => 'Van',
                'description' => 'A spacious vehicle suitable for transporting goods or multiple passengers.',
                'icon_url' => 'icons/van.png'
            ],
            [
                'name' => 'Wagon',
                'description' => 'A vehicle similar to a sedan but with an extended cargo area.',
                'icon_url' => 'icons/wagon.png'
            ],
            [
                'name' => 'Minivan',
                'description' => 'A family-oriented vehicle offering versatile seating and storage.',
                'icon_url' => 'icons/minivan.png'
            ],
        ];

        foreach ($bodyTypes as $type) {
            BodyType::create($type);
        }
    }
}
