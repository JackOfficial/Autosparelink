<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
{
    dd("Shop");
    $shop = auth()->user()->shop;

    // 1. Get last 7 days of sales (Specific to THIS Shop)
    $salesData = OrderItem::where('shop_id', $shop->id)
        ->where('created_at', '>=', now()->subDays(7))
        ->whereHas('order.payment', function ($query) {
            $query->where('status', 'successful');
        })
        ->selectRaw('DATE(created_at) as date, SUM(quantity * unit_price) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->pluck('total', 'date');

    // 2. Fetch Low Stock Items (Logic Fixed for Shop Isolation)
    $lowStockItems = Part::where('shop_id', $shop->id)
        ->where(function($query) {
            $query->where('stock_quantity', '<', 5);
            // If you add min_threshold later, uncomment the line below:
            // ->orWhereColumn('stock_quantity', '<=', 'min_threshold');
        })
        ->limit(5)
        ->get();

    // 3. Fetch Pending Pickups for the Blue Card
    $pendingPickups = OrderItem::with(['order.user'])
        ->where('shop_id', $shop->id)
        ->where('status', 'ready_for_pickup')
        ->latest()
        ->limit(3)
        ->get()
        ->map(function($item) {
            return (object)[
                'customer_name' => $item->order->user->name ?? 'Guest Customer',
                'scheduled_at'  => $item->updated_at,
                'items_count'   => $item->quantity,
                'location'      => $item->order->shipping_address ?? 'Kigali Store'
            ];
        });

    // 4. High-Value Dashboard Metrics
    $stats = [
        'total_inventory' => Part::where('shop_id', $shop->id)->count(),
        'low_stock'       => Part::where('shop_id', $shop->id)
                                ->where('stock_quantity', '<', 5)
                                ->count(),
        'pending_pickup'  => OrderItem::where('shop_id', $shop->id)
                                ->where('status', 'ready_for_pickup')
                                ->count(),
        'total_revenue'   => OrderItem::where('shop_id', $shop->id)
                                ->whereHas('order.payment', function($q){
                                    $q->where('status', 'successful');
                                })->sum(DB::raw('quantity * unit_price')),
    ];

    // 5. Recent Activity
    $recentSales = OrderItem::with(['order.user', 'part'])
        ->where('shop_id', $shop->id)
        ->latest()
        ->take(5)
        ->get();

    // Chart Formatting
    $formattedSales = collect(range(0, 6))->mapWithKeys(function($days) {
        $date = now()->subDays($days)->format('Y-m-d');
        return [$date => 0];
    })->merge($salesData)->reverse();

    return view('shop.index', [
        'salesData'      => $formattedSales->values(),
        'salesLabels'    => $formattedSales->keys(),
        'stats'          => $stats,
        'recentSales'    => $recentSales,
        'lowStockItems'  => $lowStockItems,
        'pendingPickups' => $pendingPickups,
        'shop'           => $shop
    ]);
}
}
