<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display all orders with high-priority statuses at the top.
     */
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.part', 'payment', 'shipping', 'address'])
            ->latest('id')
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
            'orderItems.shop',          
            'orderItems.part.photos',   
            'orderItems.part.category'  
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

        $order = Order::with(['payment', 'orderItems'])->findOrFail($id);

        // 1. SECURITY GUARD: Check if the order has been paid before advancing the status
        $unpaidStatuses = ['pending', 'awaiting_commitment_fee', 'callback_requested'];
        
        if (in_array($order->status, $unpaidStatuses)) {
            $hasValidPayment = $order->payment && $order->payment->status === 'successful';

            if (!$hasValidPayment && in_array($request->status, ['processing', 'shipped', 'delivered', 'completed'])) {
                return back()->with('error', 'Action denied: Cannot advance status because the client has not paid yet.');
            }
        }

        // 2. Process the status update safely
        try {
            DB::transaction(function () use ($request, $order) {
                // If the admin is trying to cancel the order
                if ($request->status === 'canceled') {
                    $isPaid = $order->payment && $order->payment->status === 'successful';
                    
                    if ($isPaid) {
                        throw new \Exception('Cannot cancel an order that has already been paid. Please initiate a refund first.');
                    }

                    foreach ($order->orderItems as $item) {
                        $item->update(['status' => 'canceled']);
                    }
                }

                // --- FIX: HANDLE COMPLETION VIA DROP-DOWN UPDATE ---
                if ($request->status === 'completed') {
                    // Query directly with row-locking to circumvent any stale relationship caches
                    $items = OrderItem::where('order_id', $order->id)
                        ->lockForUpdate()
                        ->get();

                    foreach ($items as $item) {
                        if ($item->status !== 'completed') {
                            $item->status = 'completed';
                            $item->save(); // Explicit individual save triggers your Observer cleanly
                        }
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
        $order = Order::findOrFail($id);

        if (!in_array($order->status, ['delivered', 'shipped'])) {
            return back()->with('error', 'Only delivered or shipped orders can be finalized.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'completed']);

            // FIX: Add explicit row-level verification query locking
            $items = OrderItem::where('order_id', $order->id)
                ->lockForUpdate()
                ->get();

            foreach ($items as $item) {
                if ($item->status !== 'completed') {
                    $item->status = 'completed';
                    $item->save(); 
                }
            }
        });

        return back()->with('success', 'Order finalized and vendor payments released.');
    }

    public function destroy(string $id)
    {
        $order = Order::with('payment')->findOrFail($id);
        
        if ($order->payment && $order->payment->status === 'successful') {
            return back()->with('error', 'Cannot delete an order with a successful payment. Cancel it instead.');
        }

        $order->delete();
        
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }
}