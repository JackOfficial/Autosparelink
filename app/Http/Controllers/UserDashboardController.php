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
    /**
     * Main Dashboard View
     */
 public function index()
{
    $user = Auth::user();

    // 1. Fetch Orders with relationships for the UI
    $allOrders = $user->orders()
        ->with(['orderItems.part', 'payment', 'shipping', 'address'])
        ->latest()
        ->get();

    // 2. Fetch Tickets (The missing part!)
    // We fetch the latest 5 to show a "Recent Tickets" table on the dashboard
    $tickets = $user->tickets()->latest()->take(5)->get();

    // 3. Dashboard Statistics
    $stats = [
        'total_orders'  => $allOrders->count(),
        'active_orders' => $allOrders->whereIn('status', ['pending', 'shipped', 'processing'])->count(),
        'total_spent'   => $allOrders->where('status', 'completed')->sum('total_amount'),
        'last_order'    => $allOrders->first(),
        'open_tickets'  => $user->tickets()->where('status', 'open')->count(), // For the UI badge
    ];

    // 4. Cart & Wishlist Content
    $wishlistItems = Cart::instance('wishlist')->content();
    $cartItems     = Cart::instance('default')->content();
    $cartTotal     = Cart::instance('default')->subtotal();

    // 5. Return View with 'tickets' added to compact
    return view('dashboard.index', compact(
        'user', 
        'allOrders', 
        'stats', 
        'wishlistItems', 
        'cartItems', 
        'cartTotal',
        'tickets' // Variable now passed to Blade
    ));
}

    /**
     * Show Profile Edit Form
     */
    public function editProfile()
    {
        $user = Auth::user();
        $address = $user->address; 

        return view('dashboard.edit-profile', compact('user', 'address'));
    }

    /**
     * Update User Profile and Address
     */
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

        $user->update($request->only('name', 'email'));

        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only('phone', 'street_address', 'city')
        );

        return back()->with('success', 'Profile and shipping details updated!');
    }

    /**
     * Change User Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Security updated: Your password has been changed.');
    }

    /**
     * Update Vehicle Garage Details
     */
    public function updateGarage(Request $request)
    {
        $request->validate([
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
        ]);

        Auth::user()->vehicles()->updateOrCreate(
            ['user_id' => Auth::id()], 
            $request->only('make', 'model', 'year', 'engine')
        );

        return back()->with('success', 'Garage updated! We will prioritize parts for this vehicle.');
    }

    /**
     * Notifications Management
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}