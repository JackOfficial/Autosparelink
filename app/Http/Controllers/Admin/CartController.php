<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display all users who have items saved in their database cart.
     * This is perfect for "Recovery" calls in the Rwandan market.
     */
    public function index()
    {
        // We join with the shoppingcart table used by Gloudemans package
        $users = User::whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('shoppingcart')
                  ->whereColumn('shoppingcart.identifier', 'users.id');
        })
        ->latest()
        ->paginate(15);

        return view('admin.carts.index', compact('users'));
    }

    /**
     * Display specific user's cart contents.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        // Fetch the raw cart data from the package table
        $cartData = DB::table('shoppingcart')
            ->where('identifier', $id)
            ->first();

        // The package stores data as a serialized string
        $items = $cartData ? unserialize(base64_decode($cartData->content)) : collect();

        return view('admin.carts.show', compact('user', 'items'));
    }

    /**
     * Update cart item quantity.
     * Note: Since the package stores a serialized blob, 
     * manual updates via DB are complex. Usually, Admin only views these.
     */
    public function update(Request $request, string $id)
    {
        // Typically, admins shouldn't edit a user's live cart blob directly 
        // because of serialization risks. Suggesting "View Only" or "Delete".
        return redirect()->back()->with('error', 'Manual cart editing is disabled to prevent data corruption.');
    }

    /**
     * Remove a user's entire saved cart if they ask to clear it.
     */
    public function destroy(string $id)
    {
        DB::table('shoppingcart')->where('identifier', $id)->delete();

        return redirect()->route('admin.carts.index')->with('success', 'User cart cleared successfully.');
    }
}