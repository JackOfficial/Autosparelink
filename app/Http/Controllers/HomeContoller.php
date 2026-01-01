<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index()
    {
        $parts = Part::with('photos')->latest()->take(8)->get();
        $recent_parts = Part::with('photos')->latest()->take(8)->get();
        $brands = Brand::withCount('parts')->get();
        $partsCounter = Part::count();
        $currencySymbol = 'RWF';

        return view('home', compact('parts','recent_parts','brands','partsCounter','currencySymbol'));
    }
}
