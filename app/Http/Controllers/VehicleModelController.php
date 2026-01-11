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

  public function vehicle_model(string $id){
    dd("here");
     $models = VehicleModel::with(['brand', 'variants'])
        ->where('brand_id', $id)
        ->latest()
        ->get();

    return view('models', compact('models'));
  }


  public function show(string $id)
{
    $models = VehicleModel::with(['brand', 'variants'])
        ->where('brand_id', $id)
        ->latest()
        ->get();

    return view('models', compact('models'));
  }
}