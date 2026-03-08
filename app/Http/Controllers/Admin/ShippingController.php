<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shipping;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    /**
     * Display all shippings
     */
    public function index()
    {
        $shippings = Shipping::with('order.user')
            ->latest()
            ->paginate(15);

        return view('admin.shippings.index', compact('shippings'));
    }

    /**
     * Show form to create shipping for an order
     */
    public function create()
    {
        $orders = Order::doesntHave('shipping')->get();

        return view('admin.shippings.create', compact('orders'));
    }

    /**
     * Store new shipping record
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'carrier' => 'required|string',
            'tracking_number' => 'nullable|string',
            'status' => 'required|in:pending,shipped,in_transit,delivered,failed',
        ]);

        Shipping::create([
            'order_id' => $request->order_id,
            'carrier' => $request->carrier,
            'tracking_number' => $request->tracking_number,
            'status' => $request->status,
            'shipped_at' => $request->status === 'shipped' ? now() : null,
        ]);

        return redirect()
            ->route('admin.shippings.index')
            ->with('success', 'Shipping created successfully.');
    }

    /**
     * Show single shipping
     */
    public function show(string $id)
    {
        $shipping = Shipping::with('order.user')->findOrFail($id);

        return view('admin.shippings.show', compact('shipping'));
    }

    /**
     * Edit shipping
     */
    public function edit(string $id)
    {
        $shipping = Shipping::findOrFail($id);

        return view('admin.shippings.edit', compact('shipping'));
    }

    /**
     * Update shipping
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'carrier' => 'required|string',
            'tracking_number' => 'nullable|string',
            'status' => 'required|in:pending,shipped,in_transit,delivered,failed',
        ]);

        $shipping = Shipping::findOrFail($id);

        $shipping->update([
            'carrier' => $request->carrier,
            'tracking_number' => $request->tracking_number,
            'status' => $request->status,
            'delivered_at' => $request->status === 'delivered' ? now() : null,
        ]);

        return redirect()
            ->route('admin.shippings.show', $shipping->id)
            ->with('success', 'Shipping updated successfully.');
    }

    /**
     * Delete shipping
     */
    public function destroy(string $id)
    {
        $shipping = Shipping::findOrFail($id);
        $shipping->delete();

        return redirect()
            ->route('admin.shippings.index')
            ->with('success', 'Shipping deleted successfully.');
    }
}