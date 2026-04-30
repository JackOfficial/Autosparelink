<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Brand;
use App\Models\User;
use App\Models\VehicleModel;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // 1. General Stats
        $brands = Brand::count();
        $vehicle_models = VehicleModel::count();
        $parts = Part::count();
        $users = User::count();
        $shops = Shop::count(); // Track total vendors

        // 2. Abandoned Carts
        $abandonedCount = DB::table('shoppingcart')->count();

        // 3. Orders requiring action
        $pendingOrders = Order::whereIn('status', ['pending', 'callback_requested'])->count();

        // 4. Inventory Logic (Professional UI/UX data)
        $lowStockParts = Part::where('stock_quantity', '>', 0)->where('stock_quantity', '<', 5)->count();
        $outOfStockParts = Part::where('stock_quantity', '<=', 0)->count();
        $inStockParts = Part::where('stock_quantity', '>=', 5)->count();
        $inventoryData = [$inStockParts, $lowStockParts, $outOfStockParts];

        // 5. PLATFORM REVENUE: Your total markup earnings (Admin Profit)
        // Calculating commission_amount from completed order items
        $totalAdminEarnings = OrderItem::where('status', 'completed')->sum('commission_amount');

        // 6. Real Sales Chart Data (Markup Revenue by Month)
        $salesQuery = OrderItem::select(
                DB::raw('SUM(commission_amount) as sum'), // Chart your profit, not just volume
                DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                DB::raw("MONTH(created_at) as month_num")
            )
            ->where('status', 'completed') 
            ->whereYear('created_at', date('Y'))
            ->groupBy('month', 'month_num')
            ->orderBy('month_num', 'ASC')
            ->get();

        $salesMonths = $salesQuery->pluck('month')->toArray();
        $salesData = $salesQuery->pluck('sum')->toArray();

        if (empty($salesMonths)) {
            $salesMonths = [date('M')];
            $salesData = [0];
        }

        // 7. Growth Comparison (Gross Sales for Volume Monitoring)
        $thisMonthGross = Order::where('status', 'delivered')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        $lastMonthGross = Order::where('status', 'delivered')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_amount');
        
        $revenueChange = 0;
        if ($lastMonthGross > 0) {
            $revenueChange = (($thisMonthGross - $lastMonthGross) / $lastMonthGross) * 100;
        }

        // 8. Recent Activity
        $recentOrders = Order::with('user')->latest()->take(8)->get();

        return view('admin.index', compact(
            'brands', 
            'parts', 
            'vehicle_models', 
            'pendingOrders', 
            'abandonedCount', 
            'lowStockParts', 
            'recentOrders', 
            'users', 
            'shops',
            'salesMonths', 
            'salesData', 
            'inventoryData',
            'thisMonthGross',
            'totalAdminEarnings',
            'revenueChange'
        ));
    }
}