<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display all orders with high-priority statuses at the top.
     */
public function index()
{
    // Simply fetch all orders sorted by ID in descending order (newest first)
    $orders = Order::with(['user', 'orderItems.part', 'payment', 'shipping', 'address'])
        ->latest('id') // or ->orderBy('id', 'desc')
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
            'orderItems.shop',          // Load shop directly from the item for the name/location
            'orderItems.part.photos',   // Load photos for the product images
            'orderItems.part.category'  // Load category for the badge in the UI
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status and handle child items.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,awaiting_commitment_fee,callback_requested,processing,shipped,delivered,canceled,completed'
        ]);

        // FIX: Eager load both payment and orderItems here
        $order = Order::with(['payment', 'orderItems'])->findOrFail($id);

        // 1. SECURITY GUARD: Check if the order has been paid before advancing the status
        $unpaidStatuses = ['pending', 'awaiting_commitment_fee', 'callback_requested'];
        
        if (in_array($order->status, $unpaidStatuses)) {
            // Check if there's no payment record, or if the payment status isn't 'successful'
            $hasValidPayment = $order->payment && $order->payment->status === 'successful';

            // Block changing to actionable/advanced statuses if no payment is found
            if (!$hasValidPayment && in_array($request->status, ['processing', 'shipped', 'delivered', 'completed'])) {
                return back()->with('error', 'Action denied: Cannot advance status because the client has not paid yet.');
            }
        }

        // 2. Process the status update safely
        try {
            DB::transaction(function () use ($request, $order) {
                // 1. If the admin is trying to cancel the order
                if ($request->status === 'canceled') {
                    
                    // 2. Check if a successful payment exists
                    $isPaid = $order->payment && $order->payment->status === 'successful';
                    
                    if ($isPaid) {
                        // Throw an exception to roll back the transaction and show an error to the admin
                        throw new \Exception('Cannot cancel an order that has already been paid. Please initiate a refund first.');
                    }

                    // 3. If it's NOT paid, it's safe to cancel everything
                    foreach ($order->orderItems as $item) {
                        $item->update(['status' => 'canceled']);
                    }
                }

                // Update the main order status
                $order->update(['status' => $request->status]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', "Order status updated to " . ucfirst($request->status));
    }

    public function finalize($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);

        // Safety check: Prevent finalizing unless it was delivered or shipped
        if (!in_array($order->status, ['delivered', 'shipped'])) {
            return back()->with('error', 'Only delivered or shipped orders can be finalized.');
        }

        DB::transaction(function () use ($order) {
            // 1. Mark the main order as completed
            $order->update(['status' => 'completed']);

            // 2. Mark each item as completed individually
            // This triggers the OrderItemObserver to process the vendor payment
            foreach ($order->orderItems as $item) {
                if ($item->status !== 'completed') {
                    $item->status = 'completed';
                    $item->save(); // Individual save() triggers the model observer
                }
            }
        });

        return back()->with('success', 'Order finalized and vendor payments released.');
    }

    public function destroy(string $id)
    {
        $order = Order::with('payment')->findOrFail($id);
        
        // Safety: Check if order has successful payments before deleting
        if ($order->payment && $order->payment->status === 'successful') {
            return back()->with('error', 'Cannot delete an order with a successful payment. Cancel it instead.');
        }

        $order->delete();
        
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }
}