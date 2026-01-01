<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Part;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get all brands, latest first
        $brands = Brand::latest()->get();

        // Count of all parts
        $partsCounter = Part::count();

        // All parts with their category and brand
        $parts = Part::with(['category', 'partBrand'])->latest()->get();

        // Recent parts (limit to 10 for example)
        $recent_parts = Part::with(['category', 'partBrand'])
                            ->latest()
                            ->take(10)
                            ->get();

        return view('index', compact('brands', 'partsCounter', 'parts', 'recent_parts'));
    }
}
