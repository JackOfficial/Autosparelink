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
    /**
     * Display filtered order history for the user.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->orders()->latest();

        // 1. Filter by status if provided in the request
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 2. Search by Order Number or Part Name (via relationship)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('orderItems.part', function($sq) use ($search) {
                      $sq->where('part_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 3. Eager load specific relationships for the Index UI
        $orders = $query->with(['orderItems.part.partBrand', 'orderItems.part.category'])
                        ->paginate(15)
                        ->withQueryString(); 
      
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Display deep details for a specific order.
     */
public function show(Order $order)
{
    // 1. Security Check
    if ($order->user_id != Auth::id()) {
        abort(403);
    }

    // 2. Load relationships accurately
    $order->load([
        'orderItems.part' => function($query) {
            $query->withTrashed(); // Crucial for order history if parts get deleted
        },
        'orderItems.part.partBrand',
        'orderItems.part.category',
        'orderItems.part.photos', 
        'orderItems.shop',
        // 'orderItems.shipping' removed because it's usually on the Order, not Item
    ]);

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

                // Safety: Only process items currently in 'delivered' state
                if ($item->status !== 'delivered') {
                    continue;
                }

                if ($itemData['action'] === 'accept') {
                    $item->status = 'completed';
                    $item->notes = "Customer confirmed receipt via dashboard.";
                } else {
                    $item->status = 'disputed';
                    $item->notes = "Dispute: " . $itemData['reason'];
                }

                /**
                 * NOTE: This save() triggers your OrderItem Observer.
                 * Your Observer should check: if ($item->status === 'completed') { ...Pay Shop... }
                 */
                $item->save(); 
            }
        });

        return redirect()->route('user.orders.show', $order->id)
            ->with('success', 'Thank you. Your feedback has been recorded.');
    }

    /**
     * Cancel a pending order
     */
    public function destroy(Order $order)
    {
        if ($order->user_id != Auth::id()) abort(403);

        // Only allow cancellation if the whole order is still pending
        if ($order->status === 'pending') {
            DB::transaction(function () use ($order) {
                $order->update(['status' => 'cancelled']);
                
                // Update each item so shops see the cancellation
                $order->orderItems()->update(['status' => 'cancelled']);
                
                // Restore Stock: Since the items were never shipped, 
                // we should put the quantities back into the Part model.
                foreach ($order->orderItems as $item) {
                    $item->part()->increment('stock_quantity', $item->quantity);
                }
            });

            return back()->with('success', 'Order and items cancelled. Stock has been restored.');
        }

        return back()->with('error', 'Orders already in process cannot be cancelled.');
    }
}