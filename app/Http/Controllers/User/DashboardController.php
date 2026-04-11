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

    // 1. Fetch Orders (Paginated)
    $allOrders = $user->orders()
        ->with(['orderItems.part', 'payment', 'shipping', 'addresses'])
        ->latest()
        ->paginate(10);

    // 2. Fetch Tickets (Paginated)
    $tickets = $user->tickets()->latest()->paginate(5);

    // 3. Page-Specific Data
    // We only pass 'last_order' because the other stats are now 
    // globally available via the View Composer in AppServiceProvider.
    $lastOrder = $allOrders->first();

    // 4. Cart & Wishlist Content
    $wishlistItems = Cart::instance('wishlist')->content();
    $cartItems     = Cart::instance('default')->content();
    $cartTotal     = (float) str_replace(',', '', Cart::instance('default')->subtotal());

    return view('user.index', compact(
        'user', 
        'allOrders', 
        'lastOrder', 
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
        
        // Return a single instance. If no address exists, we pass null 
        // and the Blade '??' will handle the empty fields.
        $address = $user->addresses()->first(); 

        return view('user.edit-profile', compact('user', 'address'));
    }

    /**
     * Update User Profile and Address
     */
  public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'name'           => 'required|string|max:255',
        'email'          => 'required|email|unique:users,email,' . $user->id,
        'phone'          => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'province'       => 'required|string',
        'district'       => 'required|string',
        'sector'         => 'required|string',
        'street_address' => 'required|string|max:500',
    ]);

    // Update basic user info
    $user->update($request->only('name', 'email'));

    // Update or create the address with Rwandan geography
    $user->addresses()->updateOrCreate(
        ['user_id' => $user->id], 
        [
            'full_name'      => $user->name,
            'phone'          => $request->phone,
            'province'       => $request->province,
            'district'       => $request->district,
            'sector'         => $request->sector,
            'street_address' => $request->street_address,
            'city'           => $request->district, // Mirrored for compatibility
            'country'        => 'Rwanda'
        ]
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
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
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
            'make'  => 'required|string',
            'model' => 'required|string',
            'year'  => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'vin'   => 'nullable|string|size:17', 
        ]);

        Auth::user()->vehicles()->updateOrCreate(
            ['user_id' => Auth::id()], 
            $request->only('make', 'model', 'year', 'engine', 'vin')
        );

        return back()->with('success', 'Garage updated! We will prioritize parts for this vehicle.');
    }
}