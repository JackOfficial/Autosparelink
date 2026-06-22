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
        // Explicitly scope 'reviews.status' to eliminate column ambiguity with 'parts.status'
        $part = Part::with([
            'shop', 
            'photos', 
            'state', 
            'specifications.variant',
            'reviews' => function ($query) {
                $query->where('reviews.status', 'approved')->with('user');
            }
        ])->where('sku', $sku)->firstOrFail();

        return view('frontend.shops.parts', compact('part'));
    }
}