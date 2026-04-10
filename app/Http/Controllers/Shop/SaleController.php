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
     * Reuse your shop scope logic
     */
    private function shopOrders()
    {
        $user = Auth::user();
        if (!$user->shop) abort(403);
        
        $shopId = $user->shop->id;
        return Order::whereHas('orderItems.part', function ($query) use ($shopId) {
            $query->where('shop_id', $shopId);
        });
    }

    /**
     * Display Sales History / Report
     */
    public function index(Request $request)
    {
        $shopId = Auth::user()->shop->id;
        
        // Base query for completed or successfully paid orders
        $query = $this->shopOrders()
            ->whereIn('status', ['delivered', 'shipped', 'processing'])
            ->with(['user', 'payment', 'orderItems.part.photos']);

        // Filter by Date (e.g., Today, This Week, This Month)
        if ($request->has('period')) {
            if ($request->period == 'today') $query->whereDate('created_at', Carbon::today());
            if ($request->period == 'month') $query->whereMonth('created_at', Carbon::now()->month);
        }

        $sales = $query->latest()->paginate(20);

        // Quick Stats for the header
        $totalRevenue = $query->sum('total_amount');
        $salesCount = $query->count();

        return view('shop.sales.index', compact('sales', 'totalRevenue', 'salesCount'));
    }

    /**
     * Daily/Monthly Revenue Analytics
     * Useful for building charts in your UI
     */
    public function analytics()
    {
        $shopId = Auth::user()->shop->id;

        $revenueData = $this->shopOrders()
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
        $order = $this->shopOrders()
            ->with(['user', 'orderItems.part', 'payment', 'address'])
            ->findOrFail($id);

        return view('shop.sales.invoice', compact('order'));
    }

    /**
     * Mark a "Processing" order as "Delivered" specifically from the sales view
     */
    /**
 * Quick action to mark an order as Delivered/Finalized
 */
public function finalize(string $id)
{
    // Reuse your shop-scoped query logic for security
    $order = $this->shopOrders()->findOrFail($id);
    
    // Prevent finalizing already cancelled orders if you want
    if ($order->status === 'cancelled') {
        return back()->with('error', 'Cannot finalize a cancelled order.');
    }

    $order->update([
        'status' => 'delivered'
    ]);

    return back()->with('success', "Order #{$order->id} has been marked as completed.");
}
}