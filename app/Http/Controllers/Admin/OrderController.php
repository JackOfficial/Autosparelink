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
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Not used (orders created by users)
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Not used
     */
    public function store(Request $request)
    {
        abort(404);
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

        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update order status
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status
        ]);

        return redirect()
            ->route('orders.show', $order->id)
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Delete order (optional)
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}