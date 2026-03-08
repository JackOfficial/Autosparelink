<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\PartBrand;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
   public function brands(){
     $vehicle_brands = Brand::orderBy('brand_name')->get();
    $parts_brands   = PartBrand::orderBy('name')->get();

    return view('brands', compact('vehicle_brands', 'parts_brands'));
   }
}
