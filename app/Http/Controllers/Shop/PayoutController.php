<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Payout;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    /**
     * Helper to calculate the shop's current financial standing.
     * Strictly filters for 'completed' items within 'completed' orders.
     */
    private function getFinancialSummary()
    {
        $shopId = Auth::user()->shop->id;
        $rawRate = Commission::getRate();
        $percentage = $rawRate / 100;

        // 1. Audited Gross Revenue 
        // We sum directly from OrderItem to ensure we only count items marked completed
        $revenueData = OrderItem::where('shop_id', $shopId)
            ->where('status', 'completed')
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
                    //   ->whereHas('payment', fn($p) => $p->where('status', 'successful'));
            })
            ->selectRaw("SUM(unit_price * quantity) as total_gross")
            ->first();

        $totalGross = $revenueData->total_gross ?? 0;

        // 2. Financial Breakdown
        $totalCommission = $totalGross * $percentage;
        $netEarnings = $totalGross - $totalCommission;

        // 3. Payout Deductions (Auditing the 'Wallet' movement)
        // We include 'processing' to ensure money is "charged" while admin reviews it
        $deductions = Payout::where('shop_id', $shopId)
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->selectRaw("
                SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_withdrawn,
                SUM(CASE WHEN status IN ('pending', 'processing') THEN amount ELSE 0 END) as total_locked
            ")
            ->first();

        $withdrawn = $deductions->total_withdrawn ?? 0;
        $locked = $deductions->total_locked ?? 0;

        return [
            'totalGross'       => $totalGross,
            'commissionRate'   => $rawRate,
            'totalCommission'  => $totalCommission,
            'netEarnings'      => $netEarnings,
            'totalWithdrawn'   => $withdrawn,
            'pendingPayouts'   => $locked,
            'availableBalance' => $netEarnings - ($withdrawn + $locked)
        ];
    }

    public function index()
    {
        $summary = $this->getFinancialSummary();
        
        $payouts = Payout::forCurrentSeller()
            ->latest()
            ->paginate(15);

        return view('shop.payouts.index', array_merge($summary, [
            'payouts' => $payouts
        ]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:5000',
            'payout_method' => 'required|string|in:MTN MoMo,Airtel Money,Bank Transfer',
            'account_details' => 'required|string|max:255',
        ]);

        return DB::transaction(function () use ($request) {
            // Re-calculate summary inside the transaction to prevent double-spending
            $summary = $this->getFinancialSummary();

            if ($request->amount > $summary['availableBalance']) {
                return back()->with('error', 'Insufficient balance. Audited balance: ' . number_format($summary['availableBalance']) . ' RWF.');
            }

            // Creating the payout record "locks" the funds immediately
            Auth::user()->shop->payouts()->create([
                'amount'          => $request->amount,
                'payout_method'   => $request->payout_method,
                'account_details' => $request->account_details,
                'status'          => 'pending',
                'currency'        => 'RWF',
            ]);

            return redirect()->route('shop.payouts.index')
                ->with('success', 'Your request for ' . number_format($request->amount) . ' RWF has been submitted. Your balance has been updated.');
        });
    }
}