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
            return back()->with('vin', 'Please enter a VIN, Part Number, or Name.');
        }

        // --- PATH A: VIN SEARCH (17 Characters) ---
        if (strlen($userInput) === 17) {
            
            // 1. Check API Cache (Banks the external data for 1 month)
            $vinData = Cache::remember("vin_api_unified_{$userInput}", now()->addMonths(1), function () use ($userInput, $decoder) {
                return $this->callExternalVinApi($userInput, $decoder); 
            });

            // If API failed entirely (No record found globally or in Europe)
            if (!$vinData) {
                return back()->with('vin', 'Vehicle not found in Global or European databases.');
            }

            // 2. Check Database Match (Matches API data to local parts catalog)
            $results = Cache::remember("vin_db_match_{$userInput}", now()->addDay(), function () use ($vinService, $vinData) {
                return $vinService->findPartsByVinData($vinData);
            });

            $brandId   = $results['brand']->id   ?? $vinData['db_match']['brand_id'];
            $modelId   = $results['model']->id   ?? $vinData['db_match']['model_id'];
            $variantId = $results['variant']->id ?? $vinData['db_match']['variant_id'];

            // 3. Logic for Partial Match (Vehicle found in API but NOT in local DB)
            if (!$modelId) {
                return view('parts.index', [
                    'brandId'     => $brandId,
                    'modelId'     => null,
                    'variantId'   => null,
                    'vehicleData' => $vinData,
                    'vin'         => "We identified your {$vinData['General Information']['Year']} {$vinData['General Information']['Make']} {$vinData['General Information']['Model']}, but no matching parts were found in our catalog."
                ]);
            } 

            // 4. Full Match Success
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
            'brandId'     => null, 'modelId' => null, 'variantId' => null, 'vehicleData' => null,
            'search'      => $userInput
        ]);
    }

    private function callExternalVinApi($vin, VinDecoderService $decoder)
    {
        $apiResponse = $decoder->decodeAdvanced($vin);
        $source = 'advanced';

        if (!$apiResponse || ($apiResponse['status'] ?? '') !== 'success') {
            $apiResponse = $decoder->decodeEurope($vin);
            $source = 'europe';
        }

        // If both APIs fail, return null (Controller handles the error message)
        if (!$apiResponse || ($apiResponse['status'] ?? '') !== 'success') {
            return null;
        }

        $raw = $apiResponse['data'];

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

        $cleanModelName = head(explode('/', $modelName)); 

        // DB Matching logic remains the same
        $brand = Brand::where('brand_name', 'LIKE', $make)->first();
        
        $model = $brand ? VehicleModel::where('brand_id', $brand->id)
                    ->where(function($q) use ($cleanModelName, $modelName) {
                        $q->where('model_name', 'LIKE', '%' . $cleanModelName . '%')
                          ->orWhere('model_name', 'LIKE', '%' . $modelName . '%');
                    })->first() : null;

        $variant = ($model && !empty($trim)) 
                    ? Variant::where('vehicle_model_id', $model->id)
                        ->where('name', 'LIKE', '%' . $trim . '%')
                        ->first() 
                    : null;

        return [
            'api_source' => $source,
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