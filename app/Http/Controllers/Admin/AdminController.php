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
        $shops = Shop::count(); 

        // 2. Abandoned Carts
        $abandonedCount = DB::table('shoppingcart')->count();

        // 3. Orders requiring action
        $pendingOrders = Order::whereIn('status', ['pending', 'callback_requested'])->count();

        // 4. Inventory Logic (Professional UI/UX data)
        $lowStockParts = Part::where('stock_quantity', '>', 0)->where('stock_quantity', '<', 5)->count();
        $outOfStockParts = Part::where('stock_quantity', '<=', 0)->count();
        $inStockParts = Part::where('stock_quantity', '>=', 5)->count();
        $inventoryData = [$inStockParts, $lowStockParts, $outOfStockParts];

        // 5. PLATFORM REVENUE: Total Admin Profit via commission
        $totalAdminEarnings = OrderItem::where('status', 'completed')->sum('commission_amount');

        // 6. Real Sales Chart Data (Markup Revenue by Month)
        $salesQuery = OrderItem::select(
                DB::raw('SUM(commission_amount) as sum'),
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

        // 7. Growth Comparison (Gross Sales Month-over-Month)
        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $thisMonthGross = Order::where('status', 'delivered')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_amount');

        $lastMonthGross = Order::where('status', 'delivered')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('total_amount');
        
        $revenueChange = 0;
        if ($lastMonthGross > 0) {
            $revenueChange = (($thisMonthGross - $lastMonthGross) / $lastMonthGross) * 100;
        }

        // 8. Recent Activity (Eager load only necessary user attributes)
        $recentOrders = Order::with('user:id,name')->latest()->take(8)->get();

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