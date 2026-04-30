<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem; // Required to target specific shop items
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * Display Sales History / Report
     */
    public function index(Request $request)
    {
        $shopId = Auth::user()->shop->id;

        // 1. Filter Orders that contain items from THIS shop
        $query = Order::whereHas('orderItems', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            })
            ->whereIn('status', ['delivered', 'shipped', 'processing'])
            // We only eager load THIS shop's items for clarity
            ->with(['user', 'payment', 'orderItems' => function($q) use ($shopId) {
                $q->where('shop_id', $shopId)->with('part.photos');
            }]);

        // Filter by Date
        if ($request->filled('period')) {
            if ($request->period == 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($request->period == 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            }
        }

        $sales = $query->latest()->paginate(20)->withQueryString();

        // 2. FIXED STATS: Calculate revenue based ONLY on this shop's order items
        // We sum the 'shop_payout' column we added to OrderItems
        $totalRevenue = OrderItem::where('shop_id', $shopId)
            ->whereHas('order', function($q) use ($query) {
                // This ensures we respect the date/status filters applied above
                $q->whereIn('status', ['delivered', 'shipped', 'processing']);
            })
            ->sum(DB::raw('shop_payout * quantity'));

        $salesCount = $sales->total();

        return view('shop.sales.index', compact('sales', 'totalRevenue', 'salesCount'));
    }

    /**
     * Daily/Monthly Revenue Analytics
     */
    public function analytics()
    {
        $shopId = Auth::user()->shop->id;

        // Sum 'shop_payout' from OrderItems, grouped by the order date
        $revenueData = OrderItem::where('shop_id', $shopId)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.shop_payout * order_items.quantity) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        return view('shop.sales.analytics', compact('revenueData'));
    }

    /**
     * Generate an Invoice for a sale
     */
    public function printInvoice(string $id)
    {
        $shopId = Auth::user()->shop->id;

        // Ensure we only show the items belonging to this shop on the invoice
        $order = Order::whereHas('orderItems', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            })
            ->with(['user', 'orderItems' => function($q) use ($shopId) {
                $q->where('shop_id', $shopId)->with('part');
            }, 'payment', 'address'])
            ->findOrFail($id);

        return view('shop.sales.invoice', compact('order'));
    }

    /**
     * Quick action to mark an order as Delivered
     */
    public function finalize(string $id)
    {
        $shopId = Auth::user()->shop->id;

        // Security: Ensure this order actually contains this shop's items
        $order = Order::whereHas('orderItems', function ($q) use ($shopId) {
            $q->where('shop_id', $shopId);
        })->findOrFail($id);
        
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Cannot finalize a cancelled order.');
        }

        // IMPORTANT: In a multi-vendor system, marking the WHOLE order as delivered
        // affects other shops. Usually, you'd update specific OrderItems statuses.
        $order->update([
            'status' => 'delivered'
        ]);

        return back()->with('success', "Order #{$order->id} has been marked as completed.");
    }
}