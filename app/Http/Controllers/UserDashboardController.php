<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserDashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // 1. Fetch Orders with all relationships
    // We use a collection here so we can count statuses without multiple queries
    $allOrders = $user->orders()
        ->with(['orderItems.part', 'payment', 'shipping', 'address'])
        ->latest()
        ->get();

    // 2. Calculate Dashboard Stats (Professional Touch)
    $stats = [
        'total_orders'  => $allOrders->count(),
        'active_orders' => $allOrders->whereIn('status', ['pending', 'shipped', 'processing'])->count(),
        'total_spent'   => $allOrders->where('status', 'completed')->sum('total_amount'),
        'last_order'    => $allOrders->first(),
    ];

    // 3. Wishlist summary
    $wishlistItems = Cart::instance('wishlist')->content();

    // 4. Cart summary
    $cartItems = Cart::instance('default')->content();
    
    // Using floatval to ensure math stays clean for the UI
    $cartTotal = Cart::instance('default')->subtotal();

    return view('dashboard.index', compact(
        'user', 
        'allOrders', 
        'stats', 
        'wishlistItems', 
        'cartItems', 
        'cartTotal'
    ));
}

public function editProfile()
{
    $user = Auth::user();
    // Assuming you have an 'Address' model linked to the user
    $address = $user->address; 

    return view('dashboard.edit-profile', compact('user', 'address'));
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required|current_password',
        'password' => ['required', 'confirmed', Password::defaults()],
    ]);

    $request->user()->update([
        'password' => Hash::make($request->password),
    ]);

    return back()->with('success', 'Your password has been updated securely.');
}

public function updateGarage(Request $request)
{
    $request->validate([
        'make' => 'required|string',
        'model' => 'required|string',
        'year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
    ]);

    Auth::user()->vehicles()->updateOrCreate(
        ['user_id' => Auth::id()], // For simplicity, we update the existing profile
        $request->only('make', 'model', 'year', 'engine')
    );

    return back()->with('success', 'Your Garage has been updated! We will now prioritize parts for this vehicle.');
}

public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'street_address' => 'required|string|max:500',
        'city' => 'required|string|max:100',
    ]);

    // Update User Info
    $user->update($request->only('name', 'email'));

    // Update or Create Address (Linking to the user)
    $user->address()->updateOrCreate(
        ['user_id' => $user->id],
        $request->only('phone', 'street_address', 'city')
    );

    return back()->with('success', 'Profile and Shipping Address updated successfully!');
}

}
