<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Part;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    /**
     * Display Sales Reports for autosparelink.com
     */
    public function sales(Request $request)
    {
        $days = $request->get('period', 30);
        $startDate = Carbon::now()->subDays($days);

        // Fixed: Use 'total_amount' from your orders migration
        $salesData = Order::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $totalRevenue = $salesData->sum('total_revenue');
        $totalOrders = $salesData->sum('order_count');

        /**
         * Fetch top selling parts. 
         * Note: part_id is in order_items, so we join to get accurate sales per part.
         */
        $topParts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('parts', 'order_items.part_id', '=', 'parts.id')
            ->where('orders.status', 'completed')
            ->where('orders.created_at', '>=', $startDate)
            ->select(
                'parts.part_name',
                'order_items.part_id', 
                DB::raw('SUM(order_items.quantity) as times_sold'), 
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as revenue')
            )
            ->groupBy('order_items.part_id', 'parts.part_name')
            ->orderBy('times_sold', 'DESC')
            ->limit(5)
            ->get();

        return view('admin.reports.sales', compact(
            'salesData', 
            'totalRevenue', 
            'totalOrders', 
            'days', 
            'topParts'
        ));
    }

    /**
     * Generate PDF Report for Inventory
     */
    public function downloadInventoryPDF()
    {
        $lowStockItems = Part::where('stock_quantity', '<', 5)
            ->where('stock_quantity', '>', 0)
            ->with(['category', 'partBrand'])
            ->get();

        $outOfStockItems = Part::where('stock_quantity', 0)->get();

        $stats = [
            'total_items'   => Part::count(),
            'active_parts'  => Part::where('status', 'active')->count(),
            'total_stock'   => Part::sum('stock_quantity'),
            'inventory_val' => Part::sum(DB::raw('price * stock_quantity')), 
        ];

        $pdf = Pdf::loadView('admin.reports.pdf_inventory', [
            'lowStockItems' => $lowStockItems,
            'outOfStockItems' => $outOfStockItems,
            'stats' => $stats,
            'date' => now()->format('d M Y')
        ]);

        return $pdf->download("autosparelink-inventory-report.pdf");
    }

    /**
     * Generate PDF Report for Sales
     */
    public function downloadSalesPDF(Request $request)
    {
        $days = $request->get('period', 30);
        $startDate = Carbon::now()->subDays($days);

        $salesData = Order::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_revenue'), // Fixed column name
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $totalRevenue = $salesData->sum('total_revenue');

        $pdf = Pdf::loadView('admin.reports.pdf_sales', [
            'salesData' => $salesData,
            'totalRevenue' => $totalRevenue,
            'days' => $days,
            'date' => now()->format('d M Y')
        ]);

        return $pdf->download("autosparelink-sales-{$days}days.pdf");
    }

    /**
     * Display Inventory/Stock Reports
     */
    public function inventory()
    {
        $lowStockItems = Part::where('stock_quantity', '<', 5)
            ->where('stock_quantity', '>', 0)
            ->with(['category', 'partBrand'])
            ->get();

        $outOfStockItems = Part::where('stock_quantity', 0)->get();

        $stats = [
            'total_items'   => Part::count(),
            'active_parts'  => Part::where('status', 'active')->count(),
            'total_stock'   => Part::sum('stock_quantity'),
            'inventory_val' => Part::sum(DB::raw('price * stock_quantity')), 
        ];

        return view('admin.reports.inventory', compact('lowStockItems', 'outOfStockItems', 'stats'));
    }
}