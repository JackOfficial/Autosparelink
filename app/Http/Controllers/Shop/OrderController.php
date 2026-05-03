<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function shopOrders()
    {
        $user = Auth::user();

        if (!$user->shop) {
            abort(403, 'No shop associated with this account.');
        }

        $shopId = $user->shop->id;

        // Scope to orders that contain this shop's parts
        return Order::whereHas('orderItems', function ($query) use ($shopId) {
            $query->where('shop_id', $shopId);
        });
    }

    public function index()
    {
        $shopId = Auth::user()->shop->id;

        $orders = $this->shopOrders()
            ->with([
                'user',
                'orderItems' => function($q) use ($shopId) {
                    $q->where('shop_id', $shopId)->with('part');
                },
                'payment'
            ])
            ->latest()
            ->paginate(15);

        return view('shop.orders.index', compact('orders'));
    }

    public function show(string $id)
    {
        $shopId = Auth::user()->shop->id;

        $order = $this->shopOrders()
            ->with([
                'user', 
                'orderItems' => function($query) use ($shopId) {
                    $query->where('shop_id', $shopId)->with('part');
                },
                'payment', 
                'address'
            ])
            ->findOrFail($id);

        return view('shop.orders.show', compact('order'));
    }

    /**
     * Update order item status safely.
     */
    public function updateItemStatus(Request $request, string $orderItemId)
    {
        // 1. Validation: Strip out 'completed' so shops cannot submit it to the API
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,canceled,callback_requested'
        ]);

        $shopId = Auth::user()->shop->id;
        $newStatus = $request->status;

        return DB::transaction(function () use ($shopId, $orderItemId, $newStatus) {
            
            $item = OrderItem::where('shop_id', $shopId)
                ->lockForUpdate()
                ->findOrFail($orderItemId);

            // 2. Strict Security: Only administrators can trigger 'completed' (release payments)
            if ($newStatus === 'completed') {
                abort(403, 'Only platform administrators can complete order items and release payments.');
            }

            // 3. Callback Security: If already in callback mode, it is completely locked for the shop
            if ($item->status === 'callback_requested') {
                abort(403, 'Callbacks are handled exclusively by administrative personnel.');
            }

            // 4. Payment Security: Prevent processing unpaid or callback orders
            if (in_array($item->status, ['pending', 'callback_requested'])) {
                if (in_array($newStatus, ['processing', 'shipped', 'completed'])) {
                    abort(403, 'Cannot fulfill or process an item that has not been paid for yet.');
                }
            }

            // 5. Progression Security: If it is paid ('processing'), restrict downgrading or canceling
            if ($item->status === 'processing') {
                if (in_array($newStatus, ['pending', 'callback_requested'])) {
                    abort(403, 'Cannot change a paid item back to pending or callback status.');
                }
                if ($newStatus === 'canceled') {
                    abort(403, 'Cannot cancel an order item that has already been paid for.');
                }
            }

            // 6. Terminal State Security: Block updating locked terminal states
            if (in_array($item->status, ['completed', 'canceled'])) {
                abort(403, "Cannot modify an item that is already marked as {$item->status}.");
            }

            // Save the update using the explicit save() call to properly trigger observers
            $item->status = $newStatus;
            $item->save(); 

            return back()->with('success', "Item status updated to {$newStatus}.");
        });
    }
}