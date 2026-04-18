<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display all orders with high-priority status at the top.
     */
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.part', 'payment', 'shipping', 'address'])
            ->orderByRaw("FIELD(status, 'callback_requested') DESC")
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

public function show(string $id)
{
    $order = Order::with([
        'user', 
        'payment', 
        'shipping', 
        'address',
        'orderItems.shop', // Load shop directly from the item for the name/location
        'orderItems.part.photos', // Load photos for the product images
        'orderItems.part.category' // Load category for the badge in your UI
    ])->findOrFail($id);

    return view('admin.orders.show', compact('order'));
}

    /**
     * Update order status and handle child items.
     */
    public function update(Request $request, string $id)
{
    $request->validate([
        'status' => 'required|in:pending,processing,shipped,delivered,cancelled,callback_requested'
    ]);

    $order = Order::findOrFail($id);

    DB::transaction(function () use ($request, $order) {
        // Update the main order status
        $order->update(['status' => $request->status]);

        /**
         * IMPORTANT: We no longer auto-complete items here.
         * 'delivered' just means it reached the destination.
         * We only mark items 'completed' via the Finalize controller 
         * AFTER customer confirmation.
         */
        if ($request->status === 'cancelled') {
            // If the whole order is cancelled, mark items cancelled too.
            // This does NOT trigger the Observer's payment logic.
            foreach ($order->orderItems as $item) {
                $item->update(['status' => 'cancelled']);
            }
        }
    });

    return redirect()
        ->route('admin.orders.show', $order->id)
        ->with('success', "Order status updated to " . ucfirst($request->status));
}

public function finalize($id)
{
    $order = Order::findOrFail($id);
dd("ahangaha!");
    // Safety check: Prevent finalizing unless it was delivered or shipped
    if (!in_array($order->status, ['delivered', 'shipped'])) {
        return back()->with('error', 'Only delivered orders can be finalized.');
    }

    DB::transaction(function () use ($order) {
        // 1. Mark the main order as completed
        $order->update(['status' => 'completed']);

        // 2. Mark each item as completed 
        // THIS is what triggers your OrderItemObserver to pay the shop
        foreach ($order->orderItems as $item) {
            if ($item->status != 'completed') {
                $item->status = 'completed';
                
                $item->save(); 
            }
        }
    });

    return back()->with('success', 'Order finalized and vendor payments released.');
}

    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        
        // Safety: Check if order has successful payments before deleting
        if ($order->payment && $order->payment->status === 'successful') {
            return back()->with('error', 'Cannot delete an order with a successful payment. Cancel it instead.');
        }

        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }
}