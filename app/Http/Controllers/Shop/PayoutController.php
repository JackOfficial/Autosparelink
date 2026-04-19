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
    // Simply call the centralized model method
    return Auth::user()->shop->getFinancialAudit();
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