<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Brand;
use App\Models\User;
use App\Models\VehicleModel;
use App\Models\Order;
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

        // 2. Abandoned Carts (Gloudemans Package Table)
        $abandonedCount = DB::table('shoppingcart')->count();

        // 3. Orders requiring action: Standard Pending + New Callbacks
        $pendingOrders = Order::whereIn('status', ['Pending', 'callback_requested'])->count();

        // 4. Inventory Logic (for Doughnut Chart)
        $lowStockParts = Part::where('stock_quantity', '>', 0)->where('stock_quantity', '<', 5)->count();
        $outOfStockParts = Part::where('stock_quantity', '<=', 0)->count();
        $inStockParts = Part::where('stock_quantity', '>=', 5)->count();
        $inventoryData = [$inStockParts, $lowStockParts, $outOfStockParts];

        // 5. Real Sales Chart Data (Current Year by Month)
        $salesQuery = Order::select(
                DB::raw('SUM(total) as sum'),
                DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                DB::raw("MONTH(created_at) as month_num")
            )
            ->where('status', 'Completed') 
            ->whereYear('created_at', date('Y'))
            ->groupBy('month', 'month_num')
            ->orderBy('month_num', 'ASC')
            ->get();

        $salesMonths = $salesQuery->pluck('month')->toArray();
        $salesData = $salesQuery->pluck('sum')->toArray();

        // Fallback for empty chart (Prevents Javascript errors)
        if (empty($salesMonths)) {
            $salesMonths = [date('M')];
            $salesData = [0];
        }

        // 6. Growth Comparison (This Month vs Last Month)
        $thisMonthRevenue = Order::where('status', 'Completed')->whereMonth('created_at', Carbon::now()->month)->sum('total');
        $lastMonthRevenue = Order::where('status', 'Completed')->whereMonth('created_at', Carbon::now()->subMonth()->month)->sum('total');
        
        // Calculate percentage change
        $revenueChange = 0;
        if ($lastMonthRevenue > 0) {
            $revenueChange = (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        }

        // 7. Recent Activity
        $recentOrders = Order::latest()->take(8)->get();

        return view('admin.index', compact(
            'brands', 
            'parts', 
            'vehicle_models', 
            'pendingOrders', 
            'abandonedCount', 
            'lowStockParts', 
            'recentOrders', 
            'users', 
            'salesMonths', 
            'salesData', 
            'inventoryData',
            'thisMonthRevenue',
            'revenueChange'
        ));
    }

    // Keep your task methods as placeholders or remove them if not in use
    public function addTask(Request $request) { /* ... */ }
    public function taskDone($id) { /* ... */ }
}