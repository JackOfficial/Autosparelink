<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Part;
use App\Models\Variant;
use App\Models\VehicleModel;
use App\Services\VinDecoderService; 
use App\Services\VinSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VinController extends Controller
{
    public function search(Request $request, VinSearchService $vinService, VinDecoderService $decoder)
    {
        $userInput = strtoupper(trim($request->input('search_query')));
        
        if (empty($userInput)) {
            return back()->with('error', 'Please enter a VIN, Part Number, or Name.');
        }

        // --- PATH A: VIN SEARCH (17 Characters) ---
        if (strlen($userInput) === 17) {
            
            $vinData = Cache::remember("vin_api_unified_{$userInput}", now()->addMonths(1), function () use ($userInput, $decoder) {
                return $this->callExternalVinApi($userInput, $decoder); 
            });

            // DEBUG 1: Check the final structured data returned from the API logic
            dd('Structured VIN Data:', $vinData);

            if (!$vinData) {
                return back()->with('error', 'Vehicle not found in Global or European databases.');
            }

            $results = Cache::remember("vin_db_match_{$userInput}", now()->addDay(), function () use ($vinService, $vinData) {
                return $vinService->findPartsByVinData($vinData);
            });

            $brandId   = $results['brand']->id   ?? $vinData['db_match']['brand_id'];
            $modelId   = $results['model']->id   ?? $vinData['db_match']['model_id'];
            $variantId = $results['variant']->id ?? $vinData['db_match']['variant_id'];

            // DEBUG 2: Check if local Database IDs were matched correctly
            // dd('DB Match IDs:', ['brand' => $brandId, 'model' => $modelId, 'variant' => $variantId]);

            if (!$modelId) {
                return view('parts.index', [
                    'brandId'     => $brandId,
                    'modelId'     => null,
                    'variantId'   => null,
                    'vehicleData' => $vinData,
                    'error'       => 'Vehicle identified, but no matching parts found in our catalog.'
                ]);
            } 

            return view('parts.index', [
                'brandId'     => $brandId,
                'modelId'     => $modelId,
                'variantId'   => $variantId,
                'vehicleData' => $vinData,
                'parts'       => $results['parts'] ?? collect()
            ]);
        }

        // --- PATH B: PART NUMBER OR NAME SEARCH ---
        return view('parts.index', [
            'searchTerm'  => $userInput,
            'brandId'     => null, 'modelId' => null, 'variantId' => null, 'vehicleData' => null,
            'search'      => $userInput
        ]);
    }

    private function callExternalVinApi($vin, VinDecoderService $decoder)
    {
        // 1. Try Advanced Decoder First
        $apiResponse = $decoder->decodeAdvanced($vin);
        $source = 'advanced';

        // DEBUG 3: Check raw Advanced API response
        // dd('Advanced API Raw:', $apiResponse);

        // 2. Fallback to Europe Decoder if Advanced fails
        if (!$apiResponse || ($apiResponse['status'] ?? '') !== 'success') {
            $apiResponse = $decoder->decodeEurope($vin);
            $source = 'europe';
            
            // DEBUG 4: Check raw Europe API response if fallback triggered
            // dd('Europe API Raw (Fallback):', $apiResponse);
        }

        if (!$apiResponse || ($apiResponse['status'] ?? '') !== 'success') {
            return null;
        }

        $raw = $apiResponse['data'];

        // 3. Standardize Data Extraction
        if ($source === 'europe') {
            $make      = $raw['General Information']['Make'] ?? null;
            $modelName = $raw['General Information']['Model'] ?? null;
            $year      = $raw['General Information']['Year'] ?? null;
            $trim      = $raw['General Information']['Trim level'] ?? null;
            $engine    = $raw['General Information']['Engine type'] ?? null;
            $fuel      = $raw['General Information']['Fuel type'] ?? null;
            $trans     = $raw['General Information']['Transmission'] ?? null;
            $country   = $raw['Manufacturer']['Country'] ?? null;
        } else {
            $make      = $raw['make'] ?? null;
            $modelName = $raw['model'] ?? null;
            $year      = $raw['year'] ?? null;
            $trim      = $raw['trim'] ?? null;
            $engine    = $raw['engine'] ?? null;
            $fuel      = $raw['fuel_type'] ?? null;
            $trans     = $raw['transmission'] ?? null;
            $country   = $raw['plant_country'] ?? null;
        }

        // 4. DB Matching
        $brand = Brand::where('brand_name', 'LIKE', $make)->first();
        $model = $brand ? VehicleModel::where('brand_id', $brand->id)
                    ->where('model_name', 'LIKE', '%' . $modelName . '%')
                    ->first() : null;
        $variant = $model ? Variant::where('vehicle_model_id', $model->id)
                    ->where('name', 'LIKE', '%' . ($trim ?? '') . '%')
                    ->first() : null;

        return [
            'api_source' => $source, // Added this to help you see which one worked
            'General Information' => [
                'Make'         => $make,
                'Model'        => $modelName,
                'Year'         => $year,
                'Trim'         => $trim,
                'Engine type'  => $engine,
                'Fuel type'    => $fuel,
                'Transmission' => $trans,
            ],
            'Manufacturer' => [
                'Country' => $country
            ],
            'db_match' => [
                'brand_id'   => $brand->id ?? null,
                'model_id'   => $model->id ?? null,
                'variant_id' => $variant->id ?? null,
            ]
        ];
    }
}