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
       // Get the clicked variant with its model and brand
    $variant = Variant::with(['vehicleModel.brand', 'vehicleModel.variants'])->findOrFail($id);

    // Get the model of the clicked variant
    $model = $variant->vehicleModel;

    // All variants of the same model
    $variants = $model->variants;

    return view('variation-specification', compact('variant', 'model', 'variants'));
  }

}
