<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function shopOrders()
    {
        $user = Auth::user();

        if (!$user->shop) {
            abort(403, 'No shop associated with this account.');
        }

        $shopId = $user->shop->id;

        // Scope to orders that contain this shop's parts
        return Order::whereHas('orderItems', function ($query) use ($shopId) {
            $query->where('shop_id', $shopId);
        });
    }

    public function index()
    {
        $shopId = Auth::user()->shop->id;

        $orders = $this->shopOrders()
            ->with([
                'user',
                'orderItems' => function($q) use ($shopId) {
                    // Only load this shop's items to prevent cross-shop data leaks
                    $q->where('shop_id', $shopId)->with('part');
                },
                'payment'
            ])
            ->latest()
            ->paginate(15);

        return view('shop.orders.index', compact('orders'));
    }

    public function show(string $id)
    {
        $shopId = Auth::user()->shop->id;

        $order = $this->shopOrders()
            ->with([
                'user', 
                'orderItems' => function($query) use ($shopId) {
                    $query->where('shop_id', $shopId)->with('part');
                },
                'payment', 
                'address'
            ])
            ->findOrFail($id);

        return view('shop.orders.show', compact('order'));
    }

    /**
     * Update order item status
     * In a multi-vendor system, shops update ITEMS, not the whole ORDER.
     */
    public function updateItemStatus(Request $request, string $orderItemId)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        $shopId = Auth::user()->shop->id;

        // Securely find the item belonging to this shop
        $item = OrderItem::where('shop_id', $shopId)->findOrFail($orderItemId);

        // Wrap in transaction as per your saved logic for payment safety
        DB::transaction(function () use ($item, $request) {
            // lockForUpdate prevents race conditions during payment processing
            $item->lockForUpdate();
            
            $item->status = $request->status;
            
            // Your OrderItemObserver or model booted logic will see 'isDirty' 
            // and trigger the WalletTransaction if status is 'completed'
            $item->save(); 
        });

        return back()->with('success', "Item status updated to {$request->status}.");
    }

    /**
     * Delete logic remains the same (Soft delete from shop view only)
     */
    public function destroy(string $id)
    {
        $order = $this->shopOrders()->findOrFail($id);
        $order->delete();

        return redirect()->route('shop.orders.index')->with('success', 'Order view removed.');
    }
}