<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display all orders
     */
    public function index()
    {
        $orders = Order::with([
                'user',
                'orderItems.part',
                'payment',
                'shipping',
                'address'
            ])
            ->latest()
            // We keep the urgent "Callback Requested" orders at the top
            ->orderByRaw("FIELD(status, 'callback_requested') DESC")
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display specific order
     */
    public function show(string $id)
    {
        $order = Order::with([
                'user',
                'orderItems.part',
                'payment',
                'shipping',
                'address'
            ])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show edit page (update order status)
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        
        // Define available statuses for the view dropdown
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'callback_requested'];

        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    /**
     * Update order status
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            // Added 'callback_requested' to validation
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,callback_requested'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status
        ]);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', "Order #{$order->id} status updated to " . ucfirst($request->status));
    }

    /**
     * Delete order
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}