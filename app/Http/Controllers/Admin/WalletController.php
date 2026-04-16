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
     */
    public function index()
    {
        $wallets = Wallet::with('shop')->latest('last_transaction_at')->paginate(15);
        return view('admin.wallets.index', compact('wallets'));
    }

    /**
     * Show the form for manual adjustment (e.g., adding bonus or manual deduction).
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
            'shop_id' => 'required|exists:shops,id',
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $wallet = Wallet::firstOrCreate(['shop_id' => $request->shop_id]);

                // 1. Create the Transaction Record
                $transaction = new WalletTransaction([
                    'wallet_id' => $wallet->id,
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'description' => $request->description,
                    'status' => 'completed',
                    'reference_type' => 'AdminAdjustment', // Identifies this was manual
                    'reference_id' => auth()->id(), 
                ]);
                $transaction->save();

                // 2. Update the Wallet Balance
                if ($request->type === 'credit') {
                    $wallet->increment('balance', $request->amount);
                } else {
                    if (!$wallet->canWithdraw($request->amount)) {
                        throw new \Exception('Insufficient shop balance for this debit.');
                    }
                    $wallet->decrement('balance', $request->amount);
                }

                $wallet->update(['last_transaction_at' => now()]);
            });

            return redirect()->route('admin.wallets.index')->with('success', 'Wallet adjusted successfully.');
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
     * Optional: Edit a specific transaction description if needed.
     */
    public function edit(string $id)
    {
        $transaction = WalletTransaction::findOrFail($id);
        return view('admin.wallets.edit_transaction', compact('transaction'));
    }

    /**
     * Update transaction metadata (Note: Never update the 'amount' directly for audit integrity).
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['description' => 'required|string|max:255']);
        
        $transaction = WalletTransaction::findOrFail($id);
        $transaction->update(['description' => $request->description]);

        return redirect()->back()->with('success', 'Transaction updated.');
    }

    /**
     * Standard Admin practices: We generally do not 'destroy' wallets.
     * We just deactivate the Shop instead.
     */
    public function destroy(string $id)
    {
        return back()->with('error', 'Wallets cannot be deleted for audit reasons. Deactivate the shop instead.');
    }
}
