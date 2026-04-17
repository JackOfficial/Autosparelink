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
        $order = Order::with(['user', 'orderItems.part.shop', 'payment', 'shipping', 'address'])
            ->findOrFail($id);

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

        // Use a transaction to ensure everything stays in sync
        DB::transaction(function () use ($request, $order) {
            
            $order->update(['status' => $request->status]);

            /**
             * FINANCIAL INTEGRATION:
             * If the Admin marks the WHOLE order as 'delivered', we should 
             * probably mark all items as 'completed' so the vendors get paid.
             */
            if ($request->status === 'delivered') {
                foreach ($order->orderItems as $item) {
                    // This triggers the OrderItemObserver, which creates the WalletTransaction,
                    // which then updates the Wallet balance. All automatically!
                    $item->update(['status' => 'completed']);
                }
            }
            
            // Handle Cancellation (Optional: you might want to reverse payments here)
            if ($request->status === 'cancelled') {
                $order->orderItems()->update(['status' => 'cancelled']);
            }
        });

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', "Order status updated and relevant vendor payments processed.");
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