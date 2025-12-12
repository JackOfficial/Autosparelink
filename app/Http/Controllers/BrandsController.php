<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\PartBrands;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
   public function brands(){
     $vehicle_brands = Brand::orderBy('brand_name')->get();
    $parts_brands   = PartBrands::orderBy('name')->get();

    return view('brands', compact('vehicle_brands', 'parts_brands'));
   }
}
