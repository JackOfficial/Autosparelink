<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Fetch Orders 
        $allOrders = $user->orders()
            ->with(['orderItems.part', 'payment', 'shipping', 'addresses']) // Fixed relationship name if plural
            ->latest()
            ->paginate(10);

        // 2. Fetch Tickets 
        $tickets = $user->tickets()->latest()->paginate(5);

        // 3. Optimized Statistics Queries
        // Grouping by status in one query is smart—added 'pending_tickets' logic
        $ticketStats = $user->tickets()
            ->selectRaw("status, count(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $orderQuery = $user->orders();

        $stats = [
            'total_orders'    => $orderQuery->count(),
            'active_orders'   => (clone $orderQuery)->whereIn('status', ['pending', 'shipped', 'processing'])->count(),
            'total_spent'     => (float) (clone $orderQuery)->where('status', 'completed')->sum('total_amount'),
            'last_order'      => $allOrders->first(),
            
            'open_tickets'    => $ticketStats['open'] ?? 0,
            'pending_tickets' => $ticketStats['pending'] ?? 0, // Used for the red badge in your nav
            'closed_tickets'  => $ticketStats['closed'] ?? 0,
        ];

        // 4. Cart & Wishlist
        $wishlistItems = Cart::instance('wishlist')->content();
        $cartItems     = Cart::instance('default')->content();
        $cartTotal     = (float) str_replace(',', '', Cart::instance('default')->subtotal());

        return view('user.index', compact(
            'user', 'allOrders', 'stats', 'wishlistItems', 'cartItems', 'cartTotal', 'tickets'
        ));
    }

    public function editProfile()
    {
        $user = Auth::user();
        
        // FIX: Use first() instead of just $user->addresses to avoid the Collection error
        $address = $user->addresses()->first(); 

        return view('user.edit-profile', compact('user', 'address'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'phone'          => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10', // Added phone validation
            'street_address' => 'required|string|max:500',
            'city'           => 'required|string|max:100',
        ]);

        $user->update($request->only('name', 'email'));

        // Logic: update the first address found or create a new one
        $user->addresses()->updateOrCreate(
            ['user_id' => $user->id], // Match by user_id
            [
                'full_name'      => $user->name, // Keeping model consistent
                'phone'          => $request->phone,
                'street_address' => $request->street_address,
                'city'           => $request->city,
                'country'        => 'Rwanda' // Defaulting for your local market
            ]
        );

        return back()->with('success', 'Profile and shipping details updated!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Security updated: Your password has been changed.');
    }

    public function updateGarage(Request $request)
    {
        // Added 'vin' to validation since you mentioned VIN is important for your platform
        $request->validate([
            'make'  => 'required|string',
            'model' => 'required|string',
            'year'  => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'vin'   => 'nullable|string|size:17', 
        ]);

        Auth::user()->vehicles()->updateOrCreate(
            ['user_id' => Auth::id(), 'is_primary' => true], 
            $request->only('make', 'model', 'year', 'engine', 'vin')
        );

        return back()->with('success', 'Garage updated! We will prioritize parts for this vehicle.');
    }
}