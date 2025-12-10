<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Variant;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    public function model_specification($id){
   $model = VehicleModel::with(['brand', 'variants'])->findOrFail($id);
    return view('specification', compact('model'));
  }

  public function variant_specification($id){
    $variant = Variant::with(['vehicleModel.brand', 'vehicleModel.variants'])
                ->where('id', $id)
                ->firstOrFail();

    // Get model through the relationship
    $model = $variant->vehicleModel;           
    return view('variation-specification', compact('variant', 'model'));
  }

}
