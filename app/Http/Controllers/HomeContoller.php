<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Part;
use Illuminate\Http\Request;

class HomeContoller extends Controller
{
     function index(){
      $parts = Part::with('photos')->latest()->take(8)->get();
        $recent_parts = Part::with('photos')->latest()->take(8)->get();
        $brands = Brand::withCount('parts')->get();
        $partsCounter = Part::count();
        $currencySymbol = 'RWF';

        return view('home', compact('parts','recent_parts','brands','partsCounter','currencySymbol'));
    }
}
