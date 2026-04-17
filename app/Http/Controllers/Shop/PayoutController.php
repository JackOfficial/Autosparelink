<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payout;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    public function index()
    {
        $commissionRate = 0.10; // 10% Platform Fee

        // 1. Calculate Gross Revenue using the OrderItem scope
        $totalGross = OrderItem::forCurrentSeller()
            ->whereHas('order', function ($query) {
                $query->where('status', 'delivered')
                      ->whereHas('payment', fn($p) => $p->where('status', 'successful'));
            })
            ->get()
            ->sum('subtotal');

        // 2. Financial Breakdown
        $totalCommission = $totalGross * $commissionRate;
        $netEarnings = $totalGross - $totalCommission;

        // 3. Withdrawals using the Payout scope
        $totalWithdrawn = Payout::forCurrentSeller()
            ->where('status', 'completed')
            ->sum('amount');

        // 4. Pending withdrawals
        $pendingPayouts = Payout::forCurrentSeller()
            ->where('status', 'pending')
            ->sum('amount');

        $availableBalance = $netEarnings - $totalWithdrawn - $pendingPayouts;

        // 5. Payout History
        $payouts = Payout::forCurrentSeller()
            ->latest()
            ->paginate(15);

        return view('shop.payouts.index', compact(
            'totalGross', 
            'totalCommission', 
            'availableBalance', 
            'totalWithdrawn', 
            'pendingPayouts',
            'payouts'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5000',
            'payout_method' => 'required|string|in:MTN MoMo,Airtel Money,Bank Transfer',
            'account_details' => 'required|string|max:255',
        ]);

        // Re-calculate balance using scopes for security
        $totalGross = OrderItem::forCurrentSeller()
            ->whereHas('order', function ($query) {
                $query->where('status', 'delivered')
                      ->whereHas('payment', fn($p) => $p->where('status', 'successful'));
            })->get()->sum('subtotal');

        $netEarnings = $totalGross * 0.90;
        
        $alreadyRequested = Payout::forCurrentSeller()
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->sum('amount');

        $currentBalance = $netEarnings - $alreadyRequested;

        if ($request->amount > $currentBalance) {
            return back()->with('error', 'Insufficient balance. You currently have ' . number_format($currentBalance) . ' RWF available.');
        }

        // Use the relationship to create the payout (automatically sets shop_id)
        auth()->user()->shop->payouts()->create([
            'amount' => $request->amount,
            'payout_method' => $request->payout_method,
            'account_details' => $request->account_details,
            'status' => 'pending',
        ]);

        return redirect()->route('shop.payouts.index')
            ->with('success', 'Your payout request for ' . number_format($request->amount) . ' RWF is pending approval.');
    }
}