<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        // Use paginate instead of get() for better performance as history grows
        $orders = Auth::user()->orders()
            ->latest()
            ->paginate(15); 
      
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Display the specific order details.
     */
    public function show(Order $order)
    {
        // SECURITY: Ensure the order actually belongs to the logged-in user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load relationships if needed (e.g., ticket history or order items)
        // $order->load('items');

        return view('user.orders.show', compact('order'));
    }

    /**
     * Note: In SMM/AutoSpare platforms, 'create', 'store', 'edit', 'update'
     * are usually handled by a dedicated CheckoutController or Service API.
     * If users can manually log orders, you can implement them here.
     */

    public function destroy(Order $order)
    {
        // Usually, orders aren't deleted, just cancelled.
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
            return back()->with('success', 'Order cancelled successfully.');
        }

        return back()->with('error', 'Processed orders cannot be cancelled.');
    }
}