<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            // ---------------------------
            // ENGINE & ENGINE COMPONENTS
            // ---------------------------
            'Engine & Components' => [
                'Complete Engines',
                'Cylinder Head',
                'Engine Block',
                'Pistons & Rings',
                'Crankshaft',
                'Camshaft',
                'Timing Chain / Belt',
                'Engine Bearings',
                'Gaskets & Seals',
                'Oil Pump',
                'Valve Cover',
                'Oil Pan',
                'Engine Mounts',
                'Fuel Injectors',
                'Fuel Pumps',
                'Turbochargers',
                'Superchargers',
                'Air Filters',
                'Oil Filters',
                'Fuel Filters',
                'Coolant Sensors',
                'Knock Sensors',
                'MAP Sensors',
                'MAF Sensors',
                'Throttle Body',
                'Carburetors',
                'EGR Valves',
                'ECU Modules',
            ],

            // ---------------------------
            // COOLING SYSTEM
            // ---------------------------
            'Cooling System' => [
                'Radiators',
                'Radiator Fans',
                'Water Pumps',
                'Thermostats',
                'Intercoolers',
                'Coolant Hoses',
                'Expansion Tanks',
                'Heater Cores',
                'AC Condensers',
                'Cooling Sensors',
                'Fan Clutches',
            ],

            // ---------------------------
            // FUEL & AIR SYSTEM
            // ---------------------------
            'Fuel & Air System' => [
                'Air Intake System',
                'Air Filters',
                'Throttle Body',
                'Intake Manifolds',
                'Fuel Tanks',
                'Fuel Pumps',
                'Fuel Injectors',
                'Fuel Rails',
                'Carburetors',
                'Fuel Hoses',
                'Mass Air Flow Sensors (MAF)',
                'Manifold Air Pressure Sensors (MAP)',
            ],

            // ---------------------------
            // IGNITION & ELECTRICAL
            // ---------------------------
            'Ignition & Electrical' => [
                'Ignition Coils',
                'Spark Plugs',
                'Alternators',
                'Starters',
                'Batteries',
                'Fuse Boxes',
                'Relays',
                'Wiring Harness',
                'ECU / Computer Units',
                'Sensors',
                'Voltage Regulators',
            ],

            // ---------------------------
            // TRANSMISSION & DRIVETRAIN
            // ---------------------------
            'Transmission & Drivetrain' => [
                'Gearboxes',
                'Clutch Kits',
                'Flywheels',
                'Torque Converters',
                'Differentials',
                'Drive Shafts',
                'Axles',
                'CV Joints',
                'Transmission Mounts',
                'Shifter Cables',
                'Transfer Case',
            ],

            // ---------------------------
            // SUSPENSION
            // ---------------------------
            'Suspension System' => [
                'Shock Absorbers',
                'Struts',
                'Control Arms',
                'Ball Joints',
                'Stabilizer Links',
                'Coil Springs',
                'Leaf Springs',
                'Suspension Bushings',
                'Steering Rack',
                'Tie Rod Ends',
                'Wheel Hubs',
                'Wheel Bearings',
            ],

            // ---------------------------
            // BRAKING SYSTEM
            // ---------------------------
            'Brakes System' => [
                'Brake Pads',
                'Brake Rotors',
                'Brake Drums',
                'Brake Shoes',
                'Brake Calipers',
                'Brake Lines',
                'ABS Modules',
                'Brake Boosters',
                'Brake Fluid Reservoirs',
            ],

            // ---------------------------
            // EXHAUST SYSTEM
            // ---------------------------
            'Exhaust System' => [
                'Mufflers',
                'Catalytic Converters',
                'Exhaust Pipes',
                'O₂ Sensors',
                'Resonators',
                'Exhaust Manifolds',
            ],

            // ---------------------------
            // HVAC (HEATING & COOLING)
            // ---------------------------
            'HVAC System' => [
                'AC Compressors',
                'AC Condenser',
                'Heater Core',
                'Evaporator',
                'Blower Motors',
                'AC Lines',
            ],

            // ---------------------------
            // LIGHTING & ELECTRICAL
            // ---------------------------
            'Lighting & Electrical' => [
                'Headlights',
                'Fog Lights',
                'Tail Lights',
                'Brake Lights',
                'Indicators',
                'Interior Lights',
                'Switches & Controls',
            ],

            // ---------------------------
            // BODY PANELS & EXTERIOR
            // ---------------------------
            'Exterior Body Parts' => [
                'Front Bumpers',
                'Rear Bumpers',
                'Fenders',
                'Doors',
                'Grilles',
                'Hoods',
                'Mirrors',
                'Door Handles',
                'Window Regulators',
                'Wiper Motors',
            ],

            // ---------------------------
            // INTERIOR COMPONENTS
            // ---------------------------
            'Interior Parts' => [
                'Dashboard',
                'Seats',
                'Seat Belts',
                'Carpets',
                'Steering Wheels',
                'Center Console',
                'Switch Controls',
                'Instrument Cluster',
            ],

            // ---------------------------
            // WHEELS & TYRES
            // ---------------------------
            'Wheels & Tyres' => [
                'Alloy Rims',
                'Steel Rims',
                'Tyres',
                'Wheel Caps',
                'Wheel Lug Nuts',
                'Wheel Spacers',
            ],

            // ---------------------------
            // LUBRICANTS & FLUIDS
            // ---------------------------
            'Lubricants & Fluids' => [
                'Engine Oil',
                'Gear Oil',
                'Brake Fluid',
                'Coolant',
                'Power Steering Fluid',
                'Transmission Fluid',
            ],

            // ---------------------------
            // ACCESSORIES
            // ---------------------------
            'Accessories' => [
                'Floor Mats',
                'Phone Holders',
                'Seat Covers',
                'Steering Covers',
                'Car Covers',
                'Parking Sensors',
                'Reverse Camera',
                'Dash Cameras',
            ],
        ];

        foreach ($categories as $main => $subs) {
            $parent = Category::create([
                'category_name' => $main,
                'photo' => null,
                'parent_id' => null,
            ]);

            foreach ($subs as $sub) {
                Category::create([
                    'category_name' => $sub,
                    'photo' => null,
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
