<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Display all shop wallets with their current balances.
     * Uses centralized Shop model audit logic.
     */
    public function index()
    {
        // 1. Fetch Wallets with Shop relation (Paginated for performance)
        $wallets = Wallet::with('shop')
            ->latest('updated_at')
            ->paginate(15);

        // 2. Transform the collection to include centralized audited math
        $wallets->getCollection()->transform(function ($wallet) {
            // Call the Single Source of Truth from Shop.php
            $audit = $wallet->shop->getFinancialAudit();

            // Attach values for the Blade view
            $wallet->audited_gross      = $audit['totalGross'];
            $wallet->commission_rate    = $audit['commissionRate'];
            $wallet->audited_commission = $audit['totalCommission'];
            $wallet->audited_net        = $audit['netEarnings'];
            $wallet->audited_locked     = $audit['pendingPayouts'];
            $wallet->audited_balance    = $audit['availableBalance'];

            return $wallet;
        });

        return view('admin.wallets.index', compact('wallets'));
    }

    /**
     * Show the form for manual adjustment.
     */
    public function create()
    {
        $shops = Shop::where('is_verified', true)->pluck('shop_name', 'id');
        return view('admin.wallets.adjust', compact('shops'));
    }

    /**
     * Store a manual adjustment transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shop_id'     => 'required|exists:shops,id',
            'type'        => 'required|in:credit,debit',
            'amount'      => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $wallet = Wallet::firstOrCreate(['shop_id' => $request->shop_id]);

                // 1. Create the Transaction Record
                $wallet->transactions()->create([
                    'type'           => $request->type,
                    'amount'         => $request->amount,
                    'description'    => $request->description,
                    'status'         => 'completed',
                    'reference_type' => 'AdminAdjustment',
                    'reference_id'   => auth()->id(), 
                ]);

                // 2. Update the Wallet Balance
                if ($request->type === 'credit') {
                    $wallet->increment('balance', $request->amount);
                } else {
                    // Prevent balance from going negative if your logic requires it
                    if ($wallet->balance < $request->amount) {
                        throw new \Exception('Insufficient shop balance for this debit adjustment.');
                    }
                    $wallet->decrement('balance', $request->amount);
                }

                $wallet->update(['last_transaction_at' => now()]);

                return redirect()->route('admin.wallets.index')
                    ->with('success', 'Wallet adjusted successfully.');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show transaction history for a specific wallet.
     */
    public function show(string $id)
    {
        $wallet = Wallet::with(['shop', 'transactions' => function($q) {
            $q->latest();
        }])->findOrFail($id);

        return view('admin.wallets.show', compact('wallet'));
    }

    /**
     * Update transaction metadata only (Amount is immutable for audit integrity).
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['description' => 'required|string|max:255']);
        
        $transaction = WalletTransaction::findOrFail($id);
        $transaction->update(['description' => $request->description]);

        return redirect()->back()->with('success', 'Transaction updated.');
    }

    /**
     * Wallets are generally not destroyed for financial audit reasons.
     */
    public function destroy(string $id)
    {
        return back()->with('error', 'Wallets cannot be deleted for audit reasons. Deactivate the shop instead.');
    }
}