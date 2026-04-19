<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    /**
     * Helper to calculate the shop's current financial standing.
     * Delegates the math to the Shop model's centralized audit method.
     */
    private function getFinancialSummary()
    {
        return Auth::user()->shop->getFinancialAudit();
    }

    /**
     * Display payout history and current financial summary to the seller.
     */
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

    /**
     * Store a new payout request.
     * Uses a database transaction and re-audits balance for security.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:5000',
            'payout_method' => 'required|string|in:MTN MoMo,Airtel Money,Bank Transfer',
            'account_details' => 'required|string|max:255',
        ]);

        return DB::transaction(function () use ($request) {
            // Re-calculate summary inside the transaction to prevent race conditions
            $summary = $this->getFinancialSummary();

            if ($request->amount > $summary['availableBalance']) {
                return back()->with('error', 'Insufficient balance. Audited balance: ' . number_format($summary['availableBalance']) . ' RWF.');
            }

            // Creating the payout record immediately "locks" the funds 
            // because the audit logic includes 'pending' and 'processing' statuses.
            Auth::user()->shop->payouts()->create([
                'amount'          => $request->amount,
                'payout_method'   => $request->payout_method,
                'account_details' => $request->account_details,
                'status'          => 'pending',
                'currency'        => 'RWF',
            ]);

            return redirect()->route('shop.payouts.index')
                ->with('success', 'Your request for ' . number_format($request->amount) . ' RWF has been submitted. Your available balance has been adjusted.');
        });
    }
}