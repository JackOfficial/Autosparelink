<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * Display Sales History / Report
     */
    public function index(Request $request)
    {
        // Use the local scope for security and clarity
        $query = Order::forCurrentSeller()
            ->whereIn('status', ['delivered', 'shipped', 'processing'])
            ->with(['user', 'payment', 'orderItems.part.photos']);

        // Filter by Date
        if ($request->filled('period')) {
            if ($request->period == 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($request->period == 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            }
        }

        // Clone the query for stats before pagination
        $statsQuery = clone $query;

        $sales = $query->latest()->paginate(20)->withQueryString();

        // Quick Stats for the header
        $totalRevenue = $statsQuery->sum('total_amount');
        $salesCount = $statsQuery->count();

        return view('shop.sales.index', compact('sales', 'totalRevenue', 'salesCount'));
    }

    /**
     * Daily/Monthly Revenue Analytics
     */
    public function analytics()
    {
        $revenueData = Order::forCurrentSeller()
            ->where('status', 'delivered')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
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
        // forCurrentSeller() ensures a seller can't view other people's invoices
        $order = Order::forCurrentSeller()
            ->with(['user', 'orderItems.part', 'payment', 'address'])
            ->findOrFail($id);

        return view('shop.sales.invoice', compact('order'));
    }

    /**
     * Quick action to mark an order as Delivered/Finalized
     */
    public function finalize(string $id)
    {
        $order = Order::forCurrentSeller()->findOrFail($id);
        
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Cannot finalize a cancelled order.');
        }

        $order->update([
            'status' => 'delivered'
        ]);

        return back()->with('success', "Order #{$order->id} has been marked as completed.");
    }
}