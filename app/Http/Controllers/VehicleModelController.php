<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class VehicleModelController extends Controller
{
  public function index(){
    $models = VehicleModel::with(['brand', 'variants'])->latest()->get();
    return view('models', compact('models'));
  }

public function vehicle_model(string $id)
{
    $brand = Brand::find($id);

    // If brand doesn't exist, show a friendly fallback instead of a 500/404
    if (!$brand) {
        return view('errors.brand-not-found');
    }

    $models = VehicleModel::with(['variants.engine_type', 'variants.transmission_type'])
        ->where('brand_id', $id)
        ->latest()
        ->get();

    return view('models', compact('models', 'brand'));
}
  
}