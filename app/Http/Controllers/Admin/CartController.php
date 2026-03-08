<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CartItem;

class CartController extends Controller
{
    /**
     * Display all users that have cart items
     */
    public function index()
    {
        $users = User::withCount('cartItems')
            ->having('cart_items_count', '>', 0)
            ->latest()
            ->get();

        return view('admin.carts.index', compact('users'));
    }

    /**
     * Not needed for admin carts
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Not used (cart items are created by users)
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display specific user's cart
     */
    public function show(string $id)
    {
        $user = User::with('cartItems.part')->findOrFail($id);

        return view('admin.carts.show', compact('user'));
    }

    /**
     * Not needed for admin
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update cart item (admin can change quantity)
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->back()->with('success', 'Cart item updated successfully.');
    }

    /**
     * Remove cart item
     */
    public function destroy(string $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Cart item removed successfully.');
    }
}