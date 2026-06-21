<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\InTouchPaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    // 2. Load relationships accurately without withTrashed()
    $order->load([
        'orderItems.part.partBrand',
        'orderItems.part.category',
        'orderItems.part.photos', 
        'orderItems.shop',
    ]);

    return view('user.orders.show', compact('order'));
}

    /**
     * Handle the Bulk Inspection (Accept/Dispute)
     */
 public function handleInspection(Request $request, Order $order)
{
    if ($order->user_id != Auth::id()) {
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
            // Lock the row to prevent double-processing/double-payment
            $item = OrderItem::where('id', $itemData['id'])
                ->where('order_id', $order->id) // Security: ensure item belongs to this order
                ->lockForUpdate() 
                ->firstOrFail();

            // Skip items that aren't in a state to be inspected
            if ($item->status != 'delivered') {
                continue;
            }

            if ($itemData['action'] === 'accept') {
                $item->status = 'completed';
                $item->notes = "Customer confirmed receipt via dashboard.";
                // We use save() because your logic triggers on isDirty('status') == 'completed'
                $item->save(); 
            } else {
                $item->status = 'disputed';
                $item->notes = "Dispute: " . ($itemData['reason'] ?? 'No reason provided.');
                // Use updateQuietly if you don't want payment observers to even check this
                $item->save(); 
            }
        }
    });

    return redirect()->route('user.orders.show', $order->id)
        ->with('success', 'Thank you. Your inspection results have been submitted.');
}

   /**
     * Show the direct payment page for a specific order.
     */
    public function pay(Order $order)
    {
        // 1. Ensure ownership
        if ($order->user_id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Only allow payment if the order status is 'pending' or 'callback_requested'
        if (!in_array($order->status, ['pending', 'callback_requested'])) {
            return redirect()->route('user.orders.show', $order->id)
                ->with('error', 'This order cannot be paid at this stage.');
        }

        return view('user.orders.pay', compact('order'));
    }

    /**
     * Process the payment request via InTouch.
     */
    public function processPayment(Request $request, Order $order, InTouchPaymentService $inTouch)
    {
        if ($order->user_id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($order->status, ['pending', 'callback_requested'])) {
            return redirect()->route('user.orders.show', $order->id)
                ->with('error', 'This order cannot be paid at this stage.');
        }

        $request->validate([
            'phone' => 'required|string|max:13',
        ]);

        DB::beginTransaction();
        try {
            // Retrieve shipping address phone number or fallback to input
            $paymentPhone = $request->input('phone');
            $payableNow = $order->total_amount;

            // Trigger InTouch API payment request
            $response = $inTouch->requestPayment($paymentPhone, $payableNow, $order->order_number);

            if ($response && isset($response['success']) && $response['success'] == true) {
                // Update the transaction ID from the gateway
                $order->update([
                    'transaction_id' => $response['transactionid'] ?? null,
                    'status' => 'pending' // Transition from callback_requested to pending if needed
                ]);

                DB::commit();

                return redirect()->route('user.orders.show', $order->id)
                    ->with('message', 'Payment request sent! Please approve it on your phone.');
            } else {
                throw new \Exception($response['message'] ?? "InTouch Gateway Connection Failed");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('InTouch Payment Retry Failed: ' . $e->getMessage());
            return back()->with('error', 'Payment Error: ' . $e->getMessage());
        }
    }

public function success(Order $order)
{
    // Optional: Verify the user owns the order if they are logged in
    if (auth()->check() && $order->user_id != auth()->id()) {
        abort(403);
    }

    return view('orders.success', compact('order'));
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