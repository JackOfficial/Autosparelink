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
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,canceled'
        ]);

        $shopId = Auth::user()->shop->id;
        $newStatus = $request->status;

        // Use a database transaction to acquire a lock immediately
        return DB::transaction(function () use ($shopId, $orderItemId, $newStatus) {
            
            // 1. Lock the order item for update immediately
            $item = OrderItem::where('shop_id', $shopId)
                ->lockForUpdate()
                ->findOrFail($orderItemId);

            // 2. Security Check: If it is already processing (paid), restrict back-actions
            if ($item->status === 'processing') {
                
                // Block moving back to pending
                if ($newStatus === 'pending') {
                    abort(403, 'Cannot change a paid item back to pending.');
                }

                // Block cancelling paid items from the vendor panel
                if ($newStatus === 'canceled') {
                    abort(403, 'Cannot cancel an order item that has already been paid for.');
                }
            }

            // 3. Security Check: Block updating items that are already completed or canceled
            if (in_array($item->status, ['completed', 'canceled'])) {
                abort(403, "Cannot modify an item that is already marked as {$item->status}.");
            }

            // 4. Update and save
            $item->status = $newStatus;
            $item->save(); 

            return back()->with('success', "Item status updated to {$newStatus}.");
        });
    }
}