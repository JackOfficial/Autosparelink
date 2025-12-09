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
       return view('index', compact('brands', 'partsCounter'));
    }
}
