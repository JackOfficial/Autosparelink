<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Part; // Changed from Product to Part
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    /**
     * Display Sales Reports for autosparepart.com
     */
    public function sales(Request $request)
    {
        $days = $request->get('period', 30);
        $startDate = Carbon::now()->subDays($days);

        $salesData = Order::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $totalRevenue = $salesData->sum('total_revenue');
        $totalOrders = $salesData->sum('order_count');

        // Fetch top selling parts using your new Part model
        $topParts = Order::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->select('part_id', DB::raw('COUNT(*) as times_sold'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('part_id')
            ->orderBy('times_sold', 'DESC')
            ->with('part') // Ensure Order model has public function part()
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

    return $pdf->download("autosparepart-inventory-report.pdf");
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
            DB::raw('SUM(total_price) as total_revenue'),
            DB::raw('COUNT(*) as order_count')
        )
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

    $totalRevenue = $salesData->sum('total_revenue');

    // Load the view and pass data
    $pdf = Pdf::loadView('admin.reports.pdf_sales', [
        'salesData' => $salesData,
        'totalRevenue' => $totalRevenue,
        'days' => $days,
        'date' => now()->format('d M Y')
    ]);

    return $pdf->download("autosparepart-sales-{$days}days.pdf");
    }

    /**
     * Display Inventory/Stock Reports based on Part Model
     */
    public function inventory()
    {
        // Using 'stock_quantity' from your Part model
        $lowStockItems = Part::where('stock_quantity', '<', 5)
            ->where('stock_quantity', '>', 0)
            ->with(['category', 'partBrand']) // Eager load for better performance
            ->get();

        $outOfStockItems = Part::where('stock_quantity', 0)->get();

        $stats = [
            'total_items'   => Part::count(),
            'active_parts'  => Part::where('status', 'active')->count(),
            'total_stock'   => Part::sum('stock_quantity'),
            // Note: If you have a purchase_price in a different table, update this:
            'inventory_val' => Part::sum(DB::raw('price * stock_quantity')), 
        ];

        return view('admin.reports.inventory', compact('lowStockItems', 'outOfStockItems', 'stats'));
    }
}