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
    /**
     * Get the current user's shop or abort.
     */
    private function getShopId()
    {
        if (!Auth::user()->shop) {
            abort(403, 'You must have a registered shop to access payouts.');
        }
        return Auth::user()->shop->id;
    }

    /**
     * Display the Earnings Overview and Payout History
     */
    public function index()
    {
        $shopId = $this->getShopId();
        $commissionRate = 0.10; // 10% Platform Fee

        // 1. Calculate Gross Revenue from THIS shop's items 
        // We use sum() on the database result for better performance
        $totalGross = OrderItem::where('shop_id', $shopId)
            ->whereHas('order', function ($query) {
                $query->where('status', 'delivered')
                      ->whereHas('payment', fn($p) => $p->where('status', 'successful'));
            })
            ->get()
            ->sum('subtotal'); // Accesses the getSubtotalAttribute defined in OrderItem

        // 2. Financial Breakdown
        $totalCommission = $totalGross * $commissionRate;
        $netEarnings = $totalGross - $totalCommission;

        // 3. Calculate already completed withdrawals
        $totalWithdrawn = Payout::where('shop_id', $shopId)
            ->where('status', 'completed')
            ->sum('amount');

        // 4. Calculate Pending withdrawals (Requested but not yet approved)
        $pendingPayouts = Payout::where('shop_id', $shopId)
            ->where('status', 'pending')
            ->sum('amount');

        $availableBalance = $netEarnings - $totalWithdrawn - $pendingPayouts;

        // 5. Get Payout History for the table
        $payouts = Payout::where('shop_id', $shopId)
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

    /**
     * Handle Payout Requests
     */
    public function store(Request $request)
    {
        $shopId = $this->getShopId();

        $request->validate([
            'amount' => 'required|numeric|min:5000',
            'payout_method' => 'required|string|in:MTN MoMo,Airtel Money,Bank Transfer',
            'account_details' => 'required|string|max:255',
        ]);

        // Security: Re-calculate balance on the server side
        $totalGross = OrderItem::where('shop_id', $shopId)
            ->whereHas('order', function ($query) {
                $query->where('status', 'delivered')
                      ->whereHas('payment', fn($p) => $p->where('status', 'successful'));
            })->get()->sum('subtotal');

        $netEarnings = $totalGross * 0.90;
        
        // Sum everything already paid or currently waiting in the queue
        $alreadyRequested = Payout::where('shop_id', $shopId)
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->sum('amount');

        $currentBalance = $netEarnings - $alreadyRequested;

        // Validation: Prevent over-withdrawal
        if ($request->amount > $currentBalance) {
            return back()->with('error', 'Insufficient balance. You currently have ' . number_format($currentBalance) . ' RWF available.');
        }

        Payout::create([
            'shop_id' => $shopId,
            'amount' => $request->amount,
            'payout_method' => $request->payout_method,
            'account_details' => $request->account_details,
            'status' => 'pending',
        ]);

        return redirect()->route('shop.payouts.index')
            ->with('success', 'Your payout request for ' . number_format($request->amount) . ' RWF is pending approval.');
    }
}