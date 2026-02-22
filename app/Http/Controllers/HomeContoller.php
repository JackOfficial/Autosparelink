<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request for the homepage.
     */
    public function index(): View
    {
       // 1. Fetch data once
        $parts = Part::with('photos')->latest()->take(8)->get();
        
        // 2. Reuse collection (saves a DB hit)
        $recent_parts = $parts;

        // 3. Get deep counts via staudenmeir package
        $brands = Brand::withCount('parts')->get();

        // 4. Global counter and settings
        $partsCounter = Part::count();
        $currencySymbol = 'RWF';

        return view('index', compact(
            'parts', 
            'recent_parts', 
            'brands', 
            'partsCounter', 
            'currencySymbol'
        ));
    }
}