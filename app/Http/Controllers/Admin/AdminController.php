<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Part;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VehicleModel;
use App\Models\Order;

class AdminController extends Controller
{
    public function index(){
        $brands = Brand::count();
        $vehicle_models = VehicleModel::count();
        $parts = Part::count();
        $users = User::count();
        $recentOrders = Order::where('status', 'Pending')->count();
        $salesMonths = ["Jan", "Feb", "Mar", "Apr", "May"];
        $salesData = [120, 190, 300, 500, 200];
        $inventoryData = [300, 50, 20]; // In Stock, Low Stock, Out of Stock

        $pendingOrders = Order::where('status', 'Pending')->count();
        $lowStockParts = Part::where('stock_quantity', '<', 5)->count();
        $recentOrders = Order::latest()->take(5)->get();


        return view('admin.index', compact('brands', 'parts', 'vehicle_models', 'pendingOrders', 'lowStockParts', 'recentOrders', 'users', 'recentOrders', 'salesMonths', 'salesData', 'inventoryData'));
    }

    public function addTask(Request $request){
        Todo::create([
            'task' => $request->input('task')
        ]);
        return redirect()->back();
    }

    public function taskDone($id){
        Todo::where('id', $id)->update([
            'status' => 2
        ]);
    }
}
