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
        /**
     * Main Dashboard View
     */
public function index()
{
    $user = Auth::user();

    // 1. Fetch Orders (Paginated for the table)
    $allOrders = $user->orders()
        ->with(['orderItems.part', 'payment', 'shipping', 'address'])
        ->latest()
        ->paginate(10);

    // 2. Fetch Tickets (Paginated for the dashboard table)
    $tickets = $user->tickets()->latest()->paginate(5);

    // 3. Optimized Statistics Queries
    // Use a single query for tickets to avoid multiple database hits
    $ticketStats = $user->tickets()
        ->selectRaw("status, count(*) as total")
        ->groupBy('status')
        ->pluck('total', 'status')
        ->all();

    // Fix: We need a raw query for total stats because $allOrders is paginated
    $orderQuery = $user->orders();

    $stats = [
        'total_orders'    => $orderQuery->count(),
        'active_orders'   => (clone $orderQuery)->whereIn('status', ['pending', 'shipped', 'processing'])->count(),
        'total_spent'     => (float) (clone $orderQuery)->where('status', 'completed')->sum('total_amount'),
        'last_order'      => $allOrders->first(), // This is fine from the paginated collection
        
        // Ticket Stats using the grouped collection above
        'open_tickets'    => $ticketStats['open'] ?? 0,
        'pending_tickets' => $ticketStats['pending'] ?? 0,
        'closed_tickets'  => $ticketStats['closed'] ?? 0,
    ];

    // 4. Cart & Wishlist Content
    $wishlistItems = Cart::instance('wishlist')->content();
    $cartItems     = Cart::instance('default')->content();
    
    // Clean up cart total
    $cartTotal = (float) str_replace(',', '', Cart::instance('default')->subtotal());

    return view('user.index', compact(
        'user', 
        'allOrders', 
        'stats', 
        'wishlistItems', 
        'cartItems', 
        'cartTotal',
        'tickets'
    ));
}

    /**
     * Show Profile Edit Form
     */
    public function editProfile()
    {
        $user = Auth::user();
        $address = $user->addresses; 

        return view('user.edit-profile', compact('user', 'address'));
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

        $user->addresses()->updateOrCreate(
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
