<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Part;
use App\Models\Specification;
use App\Models\Variant;
use App\Models\VehicleModel;
use App\Services\VinDecoderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VinController extends Controller
{

public function search(Request $request)
{
    // $vehicle = [ /* ... your mocked data ... */ ];
    $data = [
    'VIN' => 'JTDKC3C3801008749',

    'VIN Analytics' => [
        'Squish VIN' => 'JTDKC3C3801',
        'Serial number' => '008749',
    ],

    'General Information' => [
        'Make' => 'TOYOTA',
        'Model' => 'VERSO S',
        'Year' => '2011',
        'Trim level' => 'S',
        'Body style' => 'MPV',
        'Engine type' => '1.4 D4-D (NLP121_)',
        'Fuel type' => 'Diesel',
        'Transmission' => '6-Speed Manual',
        'Vehicle class' => 'Compact MPV',
        'Vehicle type' => 'MPV',
        'Manufactured in' => 'Japan',
    ],

    'Manufacturer' => [
        'Manufacturer' => 'Toyota Motor Corp',
        'City' => '1 Toyota-cho Toyota -Shi',
        'Region' => 'Asia',
        'Country' => 'Japan',
    ],

    'Vehicle Specification' => [
        'Body type' => 'Hatchback',
        'Number of doors' => '5',
        'Number of seats' => '5-7',
        'Displacement SI' => 1364,
        'Displacement CID' => '83',
        'Displacement nominal' => '1.40',
        'Engine valves' => 2,
        'Engine cylinders' => '4',
        'Engine horsepower' => 90,
        'Engine kilowatts' => 66,
        'Driveline' => 'FWD',
        'Anti-lock braking system' => '',
    ],
    ];
     $vehicle = $data;

    $gen = $vehicle['General Information'];
    $spec = $vehicle['Vehicle Specification'];

    // 1. BRAND MATCH
    $brand = Brand::whereRaw('UPPER(brand_name) = ?', [strtoupper($gen['Make'])])->first();
    if (!$brand) return back()->with('vin', 'Brand not found');

    // 2. SMART MODEL MATCH
    $apiModelRaw = strtoupper($gen['Model']);
    $model = VehicleModel::where('brand_id', $brand->id)
        ->where(function($q) use ($apiModelRaw) {
            $q->whereRaw('? LIKE UPPER(CONCAT("%", model_name, "%"))', [$apiModelRaw])
              ->orWhereRaw('UPPER(model_name) LIKE ?', ["%$apiModelRaw%"]);
        })
        ->first();

    // Fallback to first word (e.g., "VERSO")
    if (!$model) {
        $firstWord = strtoupper(strtok($apiModelRaw, ' '));
        $model = VehicleModel::where('brand_id', $brand->id)
            ->whereRaw('UPPER(model_name) LIKE ?', ["%$firstWord%"])
            ->first();
    }

    if (!$model) return back()->with('vin', 'Model not found');

    // 3. SMART VARIANT SEARCH (Tokens)
    $searchTokens = collect([
        $gen['Year'],
        $gen['Trim level'],
        $gen['Body style'],
        $spec['Body type'],
        $gen['Fuel type'],
        strtok($gen['Engine type'], ' '),
        strtok($gen['Transmission'], ' '), // Changed from '-' to ' ' to get "6" from "6-Speed"
    ])->filter()->unique();

    $matchedVariant = Variant::where('vehicle_model_id', $model->id)
        ->where(function ($query) use ($searchTokens, $gen) {
            // Anchor search with the Year
            $query->where('name', 'LIKE', "%{$gen['Year']}%");

            foreach ($searchTokens as $token) {
                    $query->where('name', 'LIKE', "%{$token}%");
            }
        })->first();

    // 4. ULTIMATE FALLBACK
    if (!$matchedVariant) {
        $matchedVariant = Variant::where('vehicle_model_id', $model->id)
            ->where('name', 'LIKE', "%{$gen['Year']}%")
            ->where('name', 'LIKE', "%{$gen['Fuel type']}%")
            ->first();
    }

    dd($matchedVariant); // Use this to verify the final match!

    return view('parts.index', [
        'brandId' => $brand->id,
        'modelId' => $model->id,
        'variantId' => $matchedVariant?->id,
        'vehicleData' => $vehicle
    ]);
}

public function searchByVin(Request $request, VinDecoderService $decoder)
{
    $data = $decoder->decode($request->vin);
    
    if (!$data) return back()->with('vin', 'VIN not recognized.');

    // Match Brand
    $brand = Brand::where('brand_name', 'LIKE', $data['make'])->first();
    
    // Match Model
    $model = $brand ? VehicleModel::where('brand_id', $brand->id)
                ->where('model_name', 'LIKE', '%' . $data['model'] . '%')
                ->first() : null;

    // Match Variant
    $variant = $model ? Variant::where('vehicle_model_id', $model->id)
                ->where('name', 'LIKE', '%' . ($data['trim'] ?? '') . '%')
                ->first() : null;

    // Return the view and pass these IDs
    return view('parts.index', [
        'initialBrand' => $brand?->id,
        'initialModel' => $model?->id,
        'initialVariant' => $variant?->id,
    ]);
}


/*gemini recommendations
public function vinSearch(Request $request, VinDecoderService $decoder)
    {
        $vin = $request->input('vin');
        $vehicleData = $decoder->decode($vin);

        if (!$vehicleData) {
            return back()->with('vin', 'Vehicle not found or invalid VIN.');
        }

        // Logic to match $vehicleData['make'] to your Brand model...
        return view('search.results', compact('vehicleData'));
    }
*/ 

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
