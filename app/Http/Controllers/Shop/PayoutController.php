<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Payout;
use App\Models\Commission; // Import the Commission model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    /**
     * Helper to calculate the shop's current financial standing.
     */
    private function getFinancialSummary()
    {
        // 1. Fetch the dynamic commission rate from Admin settings
        // If getRate() returns 10, $percentage will be 0.10
        $rawRate = Commission::getRate();
        $percentage = $rawRate / 100;

        // 2. Gross Revenue (Completed OrderItems + Completed Orders + Successful Payment)
        $totalGross = OrderItem::forCurrentSeller()
            ->where('status', 'completed')
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed')
                      ->whereHas('payment', fn($p) => $p->where('status', 'successful'));
            })
            ->sum(DB::raw('unit_price * quantity'));

        // 3. Financial Breakdown using dynamic rate
        $totalCommission = $totalGross * $percentage;
        $netEarnings = $totalGross - $totalCommission;

        // 4. Payout Deductions (Everything already paid or in the pipeline)
        $deductions = Payout::forCurrentSeller()
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->selectRaw("
                SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as withdrawn,
                SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as pending
            ")
            ->first();

        $totalWithdrawn = $deductions->withdrawn ?? 0;
        $pendingPayouts = $deductions->pending ?? 0;

        return [
            'totalGross'       => $totalGross,
            'commissionRate'   => $rawRate, // Pass the raw rate (e.g., 10) to the view
            'totalCommission'  => $totalCommission,
            'netEarnings'      => $netEarnings,
            'totalWithdrawn'   => $totalWithdrawn,
            'pendingPayouts'   => $pendingPayouts,
            'availableBalance' => $netEarnings - ($totalWithdrawn + $pendingPayouts)
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
            $summary = $this->getFinancialSummary();

            if ($request->amount > $summary['availableBalance']) {
                return back()->with('error', 'Insufficient balance. You can withdraw up to ' . number_format($summary['availableBalance']) . ' RWF.');
            }

            Auth::user()->shop->payouts()->create([
                'amount'          => $request->amount,
                'payout_method'   => $request->payout_method,
                'account_details' => $request->account_details,
                'status'          => 'pending',
                'currency'        => 'RWF',
            ]);

            return redirect()->route('shop.payouts.index')
                ->with('success', 'Your request for ' . number_format($request->amount) . ' RWF has been submitted for approval.');
        });
    }
}