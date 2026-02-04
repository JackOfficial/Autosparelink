<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Part;
use App\Models\Specification;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VinController extends Controller
{

public function search(Request $request)
{
    // 1. MOCKED VIN DATA (from API)
    $vehicle = [
        'General Information' => [
            'Make' => 'TOYOTA',
            'Model' => 'VERSO S',
            'Year'  => '2011',
            'Trim level' => '', // optional
            'Body style' => 'MPV',
            'Engine type' => '1.4 D4-D (NLP121_)',
            'Fuel type' => 'Diesel',
            'Transmission' => '6-Speed Manual',
            'Manufactured in' => 'Japan',
        ],
        'Manufacturer' => [
            'Manufacturer' => 'Toyota Motor Corp',
            'Region' => 'Asia',
            'Country' => 'Japan',
        ],
        'Vehicle Specification' => [
            'Body type' => 'Hatchback',
            'Number of doors' => '5',
            'Number of seats' => '5-7',
            'Engine horsepower' => 90,
            'Driveline' => 'FWD',
        ],
    ];

    // 2. CORE IDENTIFIERS
    $make  = strtoupper(trim($vehicle['General Information']['Make']));
    $model = strtoupper(trim($vehicle['General Information']['Model']));
    $year  = (int) $vehicle['General Information']['Year'];

    $trimLevel = trim($vehicle['General Information']['Trim level'] ?? '');
    $engineType = trim($vehicle['General Information']['Engine type'] ?? '');
    $bodyType = trim($vehicle['Vehicle Specification']['Body type'] ?? '');
    $engineHP = (int) ($vehicle['Vehicle Specification']['Engine horsepower'] ?? 0);
    $driveline = trim($vehicle['Vehicle Specification']['Driveline'] ?? '');

    $country = trim($vehicle['Manufacturer']['Country'] ?? '');
    $region = trim($vehicle['Manufacturer']['Region'] ?? '');

    // 3. BRAND & VEHICLE MODEL
    $brand = Brand::whereRaw('UPPER(brand_name) = ?', [$make])->first();
    if (!$brand) {
        return back()->withErrors(['vin' => "Brand ($make) not found."]);
    }

    $vehicleModel = VehicleModel::where('brand_id', $brand->id)
        ->whereRaw('UPPER(model_name) LIKE ?', ["%$model%"])
        ->first();

    if (!$vehicleModel) {
        return back()->withErrors(['vin' => "$make $model not found."]);
    }

    // 4. FIND MATCHING SPECIFICATIONS
    $specifications = Specification::where('vehicle_model_id', $vehicleModel->id)
        ->where(function ($q) use ($year) {
            $q->whereNull('production_start')
              ->orWhere('production_start', '<=', $year);
        })
        ->orWhere(function ($q) use ($year) {
            $q->whereNull('production_end')
              ->orWhere('production_end', '>=', $year);
        })
        ->when($engineType, function ($q) use ($engineType) {
            $q->whereHas('engineType', function ($e) use ($engineType) {
                $e->whereRaw('UPPER(name) LIKE ?', ["%".strtoupper($engineType)."%"]);
            });
        })
        ->when($bodyType, function ($q) use ($bodyType) {
            $q->whereHas('bodyType', function ($b) use ($bodyType) {
                $b->whereRaw('UPPER(name) LIKE ?', ["%".strtoupper($bodyType)."%"]);
            });
        })
        ->when($engineHP, function ($q) use ($engineHP) {
            $q->where('horsepower', $engineHP);
        })
        ->get();

    if ($specifications->isEmpty()) {
        return back()->withErrors([
            'vin' => "No specifications found for $year $make $model with your VIN details."
        ]);
    }

    // 5. FETCH PARTS FOR ANY SPECIFICATION
    $parts = Part::with(['photos', 'partBrand'])
        ->whereHas('fitments', function ($q) use ($specifications, $year) {
            $q->where(function ($fit) use ($specifications) {
                $fit->whereIn('vehicle_model_id', $specifications->pluck('vehicle_model_id'))
                    ->orWhereIn('variant_id', $specifications->pluck('variant_id')->filter());
            })
            ->where(function ($yearQ) use ($year) {
                $yearQ->whereNull('start_year')->orWhere('start_year', '<=', $year);
            })
            ->where(function ($yearQ) use ($year) {
                $yearQ->whereNull('end_year')->orWhere('end_year', '>=', $year);
            });
        })
        ->paginate(12);

    // 6. SERVE parts.index
    return view('parts.index', [
        'parts' => $parts,
        'categories' => Category::withCount('parts')->get(),
        'type' => 'model',
        'context' => $vehicleModel,
    ]);
}


// public function search(Request $request)
// {
//     // $request->validate([
//     //     'vin' => ['required', 'string', 'size:17'],
//     // ]);

//     // $vin = strtoupper(trim($request->vin));
//     // $apiKey = config('services.vehicle_api.key');

//     // // 1. Get API Data
//     // $response = Http::withHeaders(['x-authkey' => $apiKey])
//     //     ->get("https://api.vehicledatabases.com/advanced-vin-decode/v2/$vin");

//     // // Fallback logic...
//     // if ($response->failed() || empty($response->json()['data'])) {
//     //     $response = Http::withHeaders(['x-authkey' => $apiKey])
//     //         ->get("https://api.vehicledatabases.com/europe-vin-decode/v2/$vin");
//     // }

// $data = [
//     'VIN' => 'JTDKC3C3801008749',

//     'VIN Analytics' => [
//         'Squish VIN' => 'JTDKC3C3801',
//         'Serial number' => '008749',
//     ],

//     'General Information' => [
//         'Make' => 'TOYOTA',
//         'Model' => 'VERSO S',
//         'Year' => '2011',
//         'Trim level' => '',
//         'Body style' => 'MPV',
//         'Engine type' => '1.4 D4-D (NLP121_)',
//         'Fuel type' => 'Diesel',
//         'Transmission' => '6-Speed Manual',
//         'Vehicle class' => 'Compact MPV',
//         'Vehicle type' => 'MPV',
//         'Manufactured in' => 'Japan',
//     ],

//     'Manufacturer' => [
//         'Manufacturer' => 'Toyota Motor Corp',
//         'City' => '1 Toyota-cho Toyota -Shi',
//         'Region' => 'Asia',
//         'Country' => 'Japan',
//     ],

//     'Vehicle Specification' => [
//         'Body type' => 'Hatchback',
//         'Number of doors' => '5',
//         'Number of seats' => '5-7',
//         'Displacement SI' => 1364,
//         'Displacement CID' => '83',
//         'Displacement nominal' => '1.40',
//         'Engine valves' => 2,
//         'Engine cylinders' => '4',
//         'Engine horsepower' => 90,
//         'Engine kilowatts' => 66,
//         'Driveline' => 'FWD',
//         'Anti-lock braking system' => '',
//     ],
// ];
// $vehicle = $data;

//     if ($response->successful() && !empty($data = $response->json()['data'])) {
        
//         // 2. Extract specific identifying traits
//         $makeName  = $data['General Information']['Make'] ?? '';
//         $modelName = $data['General Information']['Model'] ?? '';
//         $year      = $data['General Information']['Year'] ?? '';

//         // 3. Find the local Specification record
//         // This is the CRITICAL link. Your DB must have these names mapped.
//         $specification = Specification::whereHas('vehicleModel', function($q) use ($modelName) {
//                 $q->where('model_name', 'like', "%{$modelName}%");
//             })
//             ->whereHas('vehicleModel.brand', function($q) use ($makeName) {
//                 $q->where('brand_name', 'like', "%{$makeName}%");
//             })
//             ->where('year', $year) 
//             ->first();

//         if (!$specification) {
//             return back()->withErrors(['vin' => "We decoded the VIN, but don't have parts for a $year $makeName $modelName in our catalog."]);
//         }

//         // 4. Redirect to your existing PartCatalogController logic
//         // This reuses the logic you already wrote!
//         return redirect()->route('catalog.index', [
//             'type' => $specification->variant_id ? 'variant' : 'model',
//             'specification' => $specification->id,
//             'vin_context' => $vin // Optional: pass to show "Parts for your VIN: XXX"
//         ]);
//     }

//     return back()->withErrors(['vin' => 'Vehicle records not found.']);
// }
    // public function search(Request $request)
    // {
    //     $request->validate([
    //         'vin' => ['required', 'string', 'size:17'], // VINs are strictly 17 chars
    //     ]);

    //     $vin = strtoupper(trim($request->vin));
    //     $apiKey = config('services.vehicle_api.key', env('VEHICLE_API_KEY'));

    //     // 1. Try Advanced VIN Decode First
    //     $response = Http::withHeaders(['x-authkey' => $apiKey])
    //         ->get("https://api.vehicledatabases.com/advanced-vin-decode/v2/$vin");

    //     // 2. If it fails or finds nothing, try Europe Decode
    //     if ($response->failed() || empty($response->json()['data'])) {
    //         $response = Http::withHeaders(['x-authkey' => $apiKey])
    //             ->get("https://api.vehicledatabases.com/europe-vin-decode/v2/$vin");
    //     }

    //     // 3. Check if we finally got data
    //     if ($response->successful() && !empty($response->json()['data'])) {
    //         $vehicle = $response->json()['data'];
            
    //         // Logic to search your local database
    //         // $parts = Part::where('model', $vehicle['model'])->get();
    //         dd($vehicle);

    //          /*
    //     |--------------------------------------------------------------------------
    //     | Extract VIN data
    //     |--------------------------------------------------------------------------
    //     */
    //     $make  = $vehicle['General Information']['Make'] ?? null;
    //     $model = $vehicle['General Information']['Model'] ?? null;
    //     $year  = (int) ($vehicle['General Information']['Year'] ?? null);
    //     $engine_type  = $vehicle['General Information']['Engine type'] ?? null;
    //     $transmission  = $vehicle['General Information']['Transmission'] ?? null;
    //     $fuel_type  = $vehicle['General Information']['Fuel type'] ?? null;
    //     $body_type  = $vehicle['Vehicle Specification']['Body type'] ?? null;
  
    //     $region  = $vehicle['Manufacturer']['Region'] ?? null;
    //     $country  = $vehicle['Manufacturer']['Country'] ?? null;

    //     $horsepower  = $vehicle['Vehicle Specification']['Engine horsepower'] ?? null;
    //     $drive_type  = $vehicle['Vehicle Specification']['Driveline'] ?? null;
    //     $doors  = $vehicle['Vehicle Specification']['Number of doors'] ?? null;
    //     $seats  = $vehicle['Vehicle Specification']['Number of seats'] ?? null;

    //         //return view('vin.results', compact('vehicle'));
    //     }
    //     dd("Vehicle records not found in Global or European databases.");
    //     //return back()->withErrors(['vin' => 'Vehicle records not found in Global or European databases.']);
    // }
}
