<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\Variant;
use App\Models\Part;
use Illuminate\Support\Facades\Cache;

class VinSearchService
{

    
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Main logic to match API data to local parts
     */
    public function findPartsByVinData(array $data)
    {
        $gen = $data['General Information'] ?? [];
        $spec = $data['Vehicle Specification'] ?? [];

        if (empty($gen)) return null;

        // 1. Match Brand
        $brand = Brand::whereRaw('UPPER(brand_name) = ?', [strtoupper($gen['Make'])])->first();
        if (!$brand) return null;

        // 2. Match Model
        $model = $this->findModel($brand->id, $gen['Model']);
        if (!$model) return null;

        // 3. Match Variant (Fuzzy Search)
        $variant = $this->findVariant($model->id, $gen, $spec);

        // 4. Fetch Parts (with eager loading)
        $parts = collect();
        if ($variant) {
            $variantSpecs = $variant->specifications()->pluck('id');
            $parts = Part::whereHas('fitments', function($q) use ($variantSpecs) {
                $q->whereIn('specification_id', $variantSpecs);
            })
            ->with(['photos', 'category', 'partBrand'])
            ->latest()
            ->paginate(12);
        }

        return [
            'brand'   => $brand,
            'model'   => $model,
            'variant' => $variant,
            'parts'   => $parts,
        ];
    }

    private function findModel($brandId, $apiModelName)
    {
        $name = strtoupper($apiModelName);
        
        // Try exact/partial match
        $model = VehicleModel::where('brand_id', $brandId)
            ->where(function($q) use ($name) {
                $q->whereRaw('? LIKE UPPER(CONCAT("%", model_name, "%"))', [$name])
                  ->orWhereRaw('UPPER(model_name) LIKE ?', ["%$name%"]);
            })->first();

        // Fallback to first word
        if (!$model) {
            $firstWord = strtok($name, ' ');
            $model = VehicleModel::where('brand_id', $brandId)
                ->whereRaw('UPPER(model_name) LIKE ?', ["%$firstWord%"])
                ->first();
        }

        return $model;
    }

    private function findVariant($modelId, $gen, $spec)
    {
        $year = $gen['Year'] ?? null;
        $tokens = collect([$gen['Trim level'] ?? '', $spec['Body type'] ?? '', $gen['Fuel type'] ?? ''])
                    ->filter()->unique();

        $variant = Variant::where('vehicle_model_id', $modelId)
            ->when($year, fn($q) => $q->where('name', 'LIKE', "%$year%"))
            ->where(function ($query) use ($tokens) {
                foreach ($tokens as $token) {
                    if (strlen($token) > 1) $query->orWhere('name', 'LIKE', "%$token%");
                }
            })
            ->orderByRaw('LENGTH(name) DESC')
            ->first();

        // Fallbacks
        return $variant 
            ?? Variant::where('vehicle_model_id', $modelId)->where('name', 'LIKE', "%$year%")->first()
            ?? Variant::where('vehicle_model_id', $modelId)->first();
    }
    
}
