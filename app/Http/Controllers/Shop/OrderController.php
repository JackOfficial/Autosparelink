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
        // 1. Validate against exactly what the vendor can submit
        $request->validate([
            'status' => 'required|in:pending,packed,ready_for_pickup,canceled'
        ]);

        $shopId = Auth::user()->shop->id;
        $newStatus = $request->status;

        return DB::transaction(function () use ($shopId, $orderItemId, $newStatus) {
            
            $item = OrderItem::where('shop_id', $shopId)
                ->lockForUpdate()
                ->findOrFail($orderItemId);

            // 2. Terminal/Admin State Protection: Block updating items that the platform controls
            $adminControlledStatuses = [
                'ready_for_pickup', 'collected', 'at_hub', 
                'delivered', 'completed', 'canceled', 
                'disputed', 'returned'
            ];

            if (in_array($item->status, $adminControlledStatuses)) {
                abort(403, "Cannot modify an item that is already marked as " . str_replace('_', ' ', $item->status) . ".");
            }

            // 3. Payment Protection: Block moving unpaid orders forward
            // (If the customer has not paid, prevent the shop from selecting anything other than canceled/pending)
            if (!$item->order->payment || $item->order->payment->status !== 'completed') {
                if (in_array($newStatus, ['packed', 'ready_for_pickup'])) {
                    abort(403, 'Cannot fulfill or process an item that has not been paid for yet.');
                }
            }

            // 4. Fulfillment Protection: Prevent reverting once packed
            if ($item->status === 'packed' && $newStatus === 'pending') {
                abort(403, 'Cannot move a packed item back to pending status.');
            }

            // 5. Explicit Cancellation Restrictions
            if ($newStatus === 'canceled' && in_array($item->status, ['packed', 'ready_for_pickup'])) {
                abort(403, 'Cannot cancel an order item that is already packed or ready for pickup.');
            }

            // 6. Set status and save to properly trigger observers
            $item->status = $newStatus;
            $item->save(); 

            $displayStatus = str_replace('_', ' ', $newStatus);
            return back()->with('success', "Item status updated to {$displayStatus}.");
        });
    }
}