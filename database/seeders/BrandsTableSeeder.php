<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('brands')->insert([
            [
                'brand_name' => 'Hyundai',
                'brand_logo' => 'brands/hyundai.png',
                'description' => 'Hyundai Motor Company is a South Korean multinational automotive manufacturer.',
                'country' => 'South Korea',
                'website' => 'https://www.hyundai.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'KIA',
                'brand_logo' => 'brands/kia.png',
                'description' => 'Kia Corporation is South Korea’s second-largest automobile manufacturer.',
                'country' => 'South Korea',
                'website' => 'https://www.kia.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'Toyota',
                'brand_logo' => 'brands/toyota.png',
                'description' => 'Toyota Motor Corporation is one of the world’s largest automobile manufacturers.',
                'country' => 'Japan',
                'website' => 'https://www.toyota.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'Mercedes-Benz',
                'brand_logo' => 'brands/mercedes.png',
                'description' => 'Mercedes-Benz is a German luxury automotive marque and a division of Mercedes-Benz Group.',
                'country' => 'Germany',
                'website' => 'https://www.mercedes-benz.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'BMW',
                'brand_logo' => 'brands/bmw.png',
                'description' => 'Bayerische Motoren Werke AG (BMW) is a German multinational manufacturer of luxury vehicles.',
                'country' => 'Germany',
                'website' => 'https://www.bmw.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'Volkswagen (VW)',
                'brand_logo' => 'brands/vw.png',
                'description' => 'Volkswagen is a German motor vehicle manufacturer known for the Golf and Passat.',
                'country' => 'Germany',
                'website' => 'https://www.vw.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'BYD',
                'brand_logo' => 'brands/byd.png',
                'description' => 'BYD Auto is a Chinese manufacturer of electric vehicles and rechargeable batteries.',
                'country' => 'China',
                'website' => 'https://www.byd.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'Honda',
                'brand_logo' => 'brands/honda.png',
                'description' => 'Honda Motor Company is a Japanese public multinational conglomerate manufacturer of automobiles.',
                'country' => 'Japan',
                'website' => 'https://www.honda.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'Mitsubishi',
                'brand_logo' => 'brands/mitsubishi.png',
                'description' => 'Mitsubishi Motors Corporation is a Japanese multinational automobile manufacturer.',
                'country' => 'Japan',
                'website' => 'https://www.mitsubishi-motors.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'brand_name' => 'Nissan',
                'brand_logo' => 'brands/nissan.png',
                'description' => 'Nissan Motor Corporation is a Japanese multinational automobile manufacturer.',
                'country' => 'Japan',
                'website' => 'https://www.nissan.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
