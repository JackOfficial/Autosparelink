<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()
            ->latest()
            ->paginate(15); 
      
        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load items and their shipping status for the UI
        $order->load(['orderItems.part', 'orderItems.shipping']);

        return view('user.orders.show', compact('order'));
    }

    /**
     * User confirms they are happy with the item.
     * This triggers the vendor payment via the OrderItem Observer.
     */
    public function confirmReceipt(OrderItem $item)
    {
        if ($item->order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($item->status !== 'delivered') {
            return back()->with('error', 'Item must be marked as delivered before confirmation.');
        }

        $item->status = 'completed';
        $item->save();

        return back()->with('success', 'Thank you! The payment has been released to the vendor.');
    }

    /**
     * User reports a problem.
     * This MOVES the status to 'disputed', which stops the Auto-Complete Scheduler.
     */
    public function reportIssue(Request $request, OrderItem $item)
    {
        if ($item->order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // Change status to disputed
        $item->status = 'disputed';
        
        // Optionally store the reason in a 'notes' column or a separate Dispute model
        $item->notes = "Dispute raised by customer: " . $request->reason;
        $item->save();

        return back()->with('warning', 'Dispute logged. Our team will review this before any payment is released.');
    }

    public function destroy(Order $order)
    {
        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
            return back()->with('success', 'Order cancelled successfully.');
        }

        return back()->with('error', 'Processed orders cannot be cancelled.');
    }
}