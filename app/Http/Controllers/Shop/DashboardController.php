<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Part;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $shop = Auth::user()->shop;
        
        // Scope-based retrieval for strict data ownership
        $wallet = Wallet::forCurrentSeller()->first();

        // 1. CHART DATA: 7-Day Revenue (Net Earnings from Wallet)
        $salesData = WalletTransaction::forCurrentSeller()
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Map dates to ensure every day in the last week exists (fill 0s)
        $chartFinal = collect(range(0, 6))->mapWithKeys(function ($days) {
            $date = now()->subDays($days)->format('Y-m-d');
            return [$date => 0];
        })->merge($salesData)->reverse();

        // 2. INVENTORY METRICS: For Radial Bar & Stats
        $totalParts = Part::forCurrentSeller()->count();
        $lowStockCount = Part::forCurrentSeller()->where('stock_quantity', '<', 5)->count();
        $healthyCount = max(0, $totalParts - $lowStockCount);

        // 3. AGGREGATED STATS
        $stats = [
            'total_inventory'   => $totalParts,
            'low_stock'         => $lowStockCount,
            'available_balance' => $wallet?->balance ?? 0,
            'pending_balance'   => $wallet?->pending_balance ?? 0,
            'total_revenue'     => $wallet?->total_earnings ?? 0,
        ];

        return view('shop.index', [
            'shop'           => $shop,
            'wallet'         => $wallet,
            'stats'          => $stats,
            'salesData'      => $chartFinal->values(),
            'salesLabels'    => $chartFinal->keys(),
            'inventoryStats' => [$lowStockCount, $healthyCount], // Used for Radial Bar [Critical, Healthy]
            'recentSales'    => OrderItem::forCurrentSeller()
                                    ->with(['order.user', 'part'])
                                    ->latest()
                                    ->take(5)
                                    ->get(),
            'pendingPickups' => OrderItem::forCurrentSeller()
                                    ->where('status', 'ready_for_pickup')
                                    ->with(['order.user'])
                                    ->latest()
                                    ->limit(3)
                                    ->get()
                                    ->map(fn($item) => (object)[
                                        'customer_name' => $item->order->user->name ?? 'Guest Customer',
                                        'location'      => $item->order->shipping_address ?? 'Kigali Store',
                                        'items_count'   => $item->quantity,
                                        'updated_at'    => $item->updated_at,
                                    ]),
        ]);
    }
}