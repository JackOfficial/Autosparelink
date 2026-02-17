<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
        public function index()
    {
        $user = Auth::user();

        $orders = $user->orders()->with(['orderItems.part', 'payment', 'shipping', 'address'])->latest()->get();

        // Wishlist summary (assuming Cart package for wishlist)
        $wishlistItems = Cart::instance('wishlist')->content();

        // Cart summary
        $cartItems = Cart::instance('default')->content();
        $cartTotal = (float) Cart::instance('default')->subtotal();

        return view('dashboard.index', compact('user', 'orders', 'wishlistItems', 'cartItems', 'cartTotal'));
    }
}
