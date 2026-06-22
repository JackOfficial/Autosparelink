<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Part;
use Illuminate\View\View;

class PublicShopPartController extends Controller
{
    public function show(string $sku): View
    {
        // Eager load everything needed, filtering for approved ratings
        $part = Part::with([
            'shop', 
            'photos', 
            'state', 
            'specifications.variant',
            'reviews' => function ($query) {
                $query->where('status', 'approved')->with('user');
            }
        ])->where('sku', $sku)->firstOrFail();

        return view('frontend.shops.parts', compact('part'));
    }
}
