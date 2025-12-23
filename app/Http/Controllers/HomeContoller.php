<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Part;
use Illuminate\Http\Request;

class HomeContoller extends Controller
{
     function index(){
       $brands = Brand::latest()->get();
       $partsCounter = Part::count();
       $parts = Part::with(['category', 'partBrand'])->latest()->get();
       $recent_parts = Part::with(['category', 'partBrand'])->latest()->get();
       return view('index', compact('brands', 'partsCounter', 'parts', 'recent_parts'));
    }
}
