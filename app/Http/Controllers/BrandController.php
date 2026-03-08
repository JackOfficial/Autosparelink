<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Show all models for a given brand
     */
    public function showModels(Brand $brand, Request $request)
    {
        // Optionally handle search by VIN or Frame Number
        $search = $request->input('q', null);

        $modelsQuery = $brand->models()->with('variants'); // eager load variants

        if ($search) {
            // Example: filter models whose variants match search criteria
            $modelsQuery->whereHas('variants', function($q) use ($search) {
                $q->where('vin', 'like', "%{$search}%")
                  ->orWhere('frame_number', 'like', "%{$search}%");
            });
        }

        $models = $modelsQuery->get();

        return view('brand.models', compact('brand', 'models'));
    }

    public function show(){
        
    }
}
