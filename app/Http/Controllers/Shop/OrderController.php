<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Scope the query to only orders containing parts belonging to the user's shop.
     */
    private function shopOrders()
    {
        $user = Auth::user();

        // Check if the user has a shop to prevent errors
        if (!$user->shop) {
            abort(403, 'No shop associated with this account.');
        }

        $shopId = $user->shop->id;

        return Order::whereHas('orderItems.part', function ($query) use ($shopId) {
            $query->where('shop_id', $shopId);
        });
    }

    /**
     * Display shop-specific orders
     */
    public function index()
    {
        $shopId = Auth::user()->shop->id;

        $orders = $this->shopOrders()
            ->with([
                'user',
                'orderItems' => function($q) use ($shopId) {
                    // Critical: Only load items that belong to this specific shop
                    $q->whereHas('part', fn($p) => $p->where('shop_id', $shopId))
                      ->with('part');
                },
                'payment',
                'shipping'
            ])
            ->latest()
            ->orderByRaw("FIELD(status, 'callback_requested') DESC")
            ->paginate(15);

        return view('shop.orders.index', compact('orders'));
    }

    /**
     * Display specific shop order
     */
    public function show(string $id)
    {
        $order = $this->shopOrders()
            ->with(['user', 'orderItems.part', 'payment', 'shipping', 'address'])
            ->findOrFail($id);

        return view('shop.orders.show', compact('order'));
    }

    /**
     * Edit order status
     */
    public function edit(string $id)
    {
        $order = $this->shopOrders()->findOrFail($id);
        
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'callback_requested'];

        return view('shop.orders.edit', compact('order', 'statuses'));
    }

    /**
     * Update order status
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,callback_requested'
        ]);

        $order = $this->shopOrders()->findOrFail($id);
        
        $order->update([
            'status' => $request->status
        ]);

        return redirect()
            ->route('shop.orders.show', $order->id)
            ->with('success', "Order #{$order->id} updated successfully.");
    }

    /**
     * Delete order (Soft delete from shop view)
     */
    public function destroy(string $id)
    {
        $order = $this->shopOrders()->findOrFail($id);
        $order->delete();

        return redirect()
            ->route('shop.orders.index')
            ->with('success', 'Order removed successfully.');
    }
}