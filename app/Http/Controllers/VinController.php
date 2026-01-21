<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Part;
use App\Models\Specification;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VinController extends Controller
{
    public function search(Request $request)
{
    // 1️⃣ Validate input (VIN / Frame Number)
    $request->validate([
        'vin' => ['required', 'string', 'min:8', 'max:17'],
    ]);

    $vin = strtoupper(trim($request->vin));

    // 2️⃣ Decode VIN using NHTSA (FREE)
    $response = Http::get(
        "https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVin/$vin",
        ['format' => 'json']
    );

    if (!$response->ok()) {
        return back()->withErrors(['vin' => 'Unable to decode VIN at the moment.']);
    }

    // Convert API response to key => value array
    $vinData = collect($response->json('Results'))
        ->pluck('Value', 'Variable');

    // 3️⃣ Extract useful vehicle info
    $makeName  = $vinData['Make'] ?? null;
    $modelName = $vinData['Model'] ?? null;
    $year      = $vinData['Model Year'] ?? null;
    $engine    = $vinData['Engine Model'] ?? $vinData['Displacement (L)'] ?? null;
    $body      = $vinData['Body Class'] ?? null;
    $trans     = $vinData['Transmission Style'] ?? null;
    $drive     = $vinData['Drive Type'] ?? null;

    dd($vinData);

    if (!$makeName || !$modelName) {
        return back()->withErrors([
            'vin' => 'VIN decoded, but vehicle make or model could not be identified.',
        ]);
    }

    // 4️⃣ Match Brand
    $brand = Brand::where('brand_name', 'LIKE', "%$makeName%")->first();

    if (!$brand) {
        return back()->withErrors([
            'vin' => 'Vehicle brand not supported yet.',
        ]);
    }

    // 5️⃣ Match Model
    $model = VehicleModel::where('vehicle_brand_id', $brand->id)
        ->where('name', 'LIKE', "%$modelName%")
        ->first();

    if (!$model) {
        return back()->withErrors([
            'vin' => 'Vehicle model not found in our catalog.',
        ]);
    }

    // 6️⃣ Try to match SPECIFICATION (model OR variant)
    $specQuery = Specification::query()
        ->where(function ($q) use ($model) {
            $q->where('vehicle_model_id', $model->id)
              ->orWhereNotNull('variant_id');
        });

    if ($engine) {
        $specQuery->whereHas('engineType', fn ($q) =>
            $q->where('name', 'LIKE', "%$engine%")
        );
    }

    if ($body) {
        $specQuery->whereHas('bodyType', fn ($q) =>
            $q->where('name', 'LIKE', "%$body%")
        );
    }

    if ($trans) {
        $specQuery->whereHas('transmissionType', fn ($q) =>
            $q->where('name', 'LIKE', "%$trans%")
        );
    }

    if ($drive) {
        $specQuery->whereHas('driveType', fn ($q) =>
            $q->where('name', 'LIKE', "%$drive%")
        );
    }

    $specification = $specQuery->first();

    // 7️⃣ Fetch compatible parts (priority-based)
    $parts = Part::query()
        ->when($specification, function ($q) use ($specification) {
            $q->whereHas('specifications', fn ($sq) =>
                $sq->where('specification_id', $specification->id)
            );
        })
        ->orWhereHas('vehicle_models', fn ($q) =>
            $q->where('model_id', $model->id)
        )
        ->distinct()
        ->paginate(20);

    // 8️⃣ Return results
    return view('parts.vin-results', [
        'vin'           => $vin,
        'brand'         => $brand,
        'model'         => $model,
        'year'          => $year,
        'specification' => $specification,
        'parts'         => $parts,
    ]);
}
}
