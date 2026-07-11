<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            ->with(['user', 'payment', 'orderItems' => function($q) use ($shopId) {
                $q->where('shop_id', $shopId)->with('part.photos');
            }]);

        // Capture periods to reuse for accurate revenue calculation
        $periodFilter = null;
        if ($request->filled('period')) {
            $periodFilter = $request->period;
            if ($periodFilter == 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($periodFilter == 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            }
        }

        $sales = $query->latest()->paginate(20)->withQueryString();

        // 2. FIXED STATS: Target accurate matching ledger rows
        $revenueQuery = OrderItem::where('shop_id', $shopId)
            ->whereHas('order', function($q) use ($periodFilter) {
                $q->whereIn('status', ['delivered', 'shipped', 'processing']);
                
                // Synchronize date filters with main list display
                if ($periodFilter == 'today') {
                    $q->whereDate('created_at', Carbon::today());
                } elseif ($periodFilter == 'month') {
                    $q->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                }
            });

        // Sum directly without multiplying quantity again if shop_payout is already a net column total
        $totalRevenue = $revenueQuery->sum('shop_payout');

        $salesCount = $sales->total();

        return view('shop.sales.index', compact('sales', 'totalRevenue', 'salesCount'));
    }

    /**
     * Daily/Monthly Revenue Analytics
     */
    public function analytics()
    {
        $shopId = Auth::user()->shop->id;

        // Grouped metrics tracking over the last 30 active days
        $revenueData = OrderItem::where('shop_id', $shopId)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.shop_payout) as total')
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
     * Quick action to mark a single vendor's items as Completed
     */
    public function finalize(string $id)
    {
        $shopId = Auth::user()->shop->id;

        // Security check: Verify order context ownership
        $order = Order::whereHas('orderItems', function ($q) use ($shopId) {
            $q->where('shop_id', $shopId);
        })->findOrFail($id);
        
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Cannot update items for a cancelled order.');
        }

        // Isolate updates strictly to items belonging to this specific vendor shop
        $myItems = OrderItem::where('order_id', $order->id)
            ->where('shop_id', $shopId)
            ->get();

        foreach ($myItems as $item) {
            if ($item->status !== 'completed') {
                $item->status = 'completed';
                $item->save(); // Individual save triggers your vendor payment observer safely
            }
        }

        return back()->with('success', 'Your items for this order have been successfully marked as completed.');
    }
}