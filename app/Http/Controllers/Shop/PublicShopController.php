<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\View\View;

class PublicShopController extends Controller
{
    public function show(int $id): View
    {
        $shop = Shop::with([
            'parts',
            'reviews' => function ($query) {
                $query->where('status', 'approved')->with('user');
            }
        ])->findOrFail($id);

        return view('frontend.shops.show', compact('shop'));
    }
}
