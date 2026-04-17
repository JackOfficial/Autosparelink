<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(15); 
        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Load items, the spare part details, and the specific shipping info for each shop
        $order->load(['orderItems.part', 'orderItems.shop', 'orderItems.shipping']);

        return view('user.orders.show', compact('order'));
    }

    /**
     * Handle the Bulk Inspection (Accept/Dispute)
     */
    public function processInspection(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.action' => 'required|in:accept,dispute',
            'items.*.reason' => 'required_if:items.*.action,dispute|nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $order) {
            foreach ($request->items as $itemData) {
                $item = OrderItem::where('id', $itemData['id'])
                    ->where('order_id', $order->id)
                    ->firstOrFail();

                // Only process items that are currently 'delivered'
                if ($item->status !== 'delivered') {
                    continue;
                }

                if ($itemData['action'] === 'accept') {
                    $item->status = 'completed';
                    $item->notes = "Customer confirmed receipt.";
                } else {
                    $item->status = 'disputed';
                    $item->notes = "Customer reported issue: " . $itemData['reason'];
                }

                $item->save(); // Triggers the safe payment logic if status is 'completed'
            }
        });

        return redirect()->route('user.orders.show', $order->id)
            ->with('success', 'Your order inspection has been submitted successfully.');
    }

    /**
     * Legacy single item confirmation (optional, if you keep the single button)
     */
    public function confirmReceipt(OrderItem $item)
    {
        if ($item->order->user_id !== Auth::id()) abort(403);
        
        if ($item->status !== 'delivered') {
            return back()->with('error', 'Item must be delivered first.');
        }

        $item->status = 'completed';
        $item->save();

        return back()->with('success', 'Item confirmed and payment released.');
    }

    public function destroy(Order $order)
    {
        if ($order->user_id != Auth::id()) abort(403);

        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
            // Also cancel items
            $order->orderItems()->update(['status' => 'cancelled']);
            return back()->with('success', 'Order cancelled successfully.');
        }

        return back()->with('error', 'Processed orders cannot be cancelled.');
    }
}