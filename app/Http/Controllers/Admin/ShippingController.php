<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shipping;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    /**
     * Display all shippings with eager loaded data
     */
    public function index()
    {
        $shippings = Shipping::with(['order.user', 'order.address'])
            ->latest()
            ->paginate(15);

        return view('admin.shippings.index', compact('shippings'));
    }

    /**
     * Show form to create shipping for an order
     */
    public function create()
    {
        // Only get orders that are 'processing' and don't have shipping yet
        $orders = Order::where('status', 'processing')
            ->doesntHave('shipping')
            ->get();

        return view('admin.shippings.create', compact('orders'));
    }

    /**
     * Store new shipping record and update order status
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'carrier' => 'required|string',
            'tracking_number' => 'nullable|string',
            'status' => 'required|in:pending,shipped,in_transit,delivered,failed',
        ]);

        $shipping = Shipping::create([
            'order_id' => $request->order_id,
            'carrier' => $request->carrier,
            'tracking_number' => $request->tracking_number,
            'status' => $request->status,
            'shipped_at' => in_array($request->status, ['shipped', 'in_transit']) ? now() : null,
        ]);

        // Auto-update Order Status
        if (in_array($request->status, ['shipped', 'in_transit'])) {
            $shipping->order->update(['status' => 'shipped']);
        }

        return redirect()
            ->route('admin.shippings.index')
            ->with('success', "Shipping for Order #{$request->order_id} has been initiated.");
    }

    /**
     * Show single shipping details
     */
    public function show(string $id)
    {
        $shipping = Shipping::with(['order.user', 'order.address'])->findOrFail($id);

        return view('admin.shippings.show', compact('shipping'));
    }

    /**
     * Update shipping and sync with Order status
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'carrier' => 'required|string',
            'tracking_number' => 'nullable|string',
            'status' => 'required|in:pending,shipped,in_transit,delivered,failed',
        ]);

        $shipping = Shipping::findOrFail($id);
        
        $data = [
            'carrier' => $request->carrier,
            'tracking_number' => $request->tracking_number,
            'status' => $request->status,
        ];

        // Set timestamps based on status
        if ($request->status === 'delivered') {
            $data['delivered_at'] = now();
        }

        $shipping->update($data);

        // Sync Order Status: If shipping is delivered, the order is delivered.
        if ($request->status === 'delivered') {
            $shipping->order->update(['status' => 'delivered']);
        } elseif (in_array($request->status, ['shipped', 'in_transit'])) {
            $shipping->order->update(['status' => 'shipped']);
        }

        return redirect()
            ->route('admin.shippings.show', $shipping->id)
            ->with('success', 'Shipping status updated and order synced.');
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
            ->with('success', 'Shipping record removed.');
    }
}