<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Shop;
use App\Services\InTouchPaymentService; // Integrated for balance transparency
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log};

class WalletController extends Controller
{
    protected $intouchService;

    public function __construct(InTouchPaymentService $intouchService)
    {
        $this->intouchService = $intouchService;
    }

    /**
     * Display all shop wallets with their current balances.
     * Uses centralized Shop model audit logic.
     */
    public function index()
    {
        // 1. Fetch Wallets with Shop relation
        $wallets = Wallet::with('shop')
            ->latest('updated_at')
            ->paginate(15);

        // 2. Transform the collection using the Shop.php audit method
        $wallets->getCollection()->transform(function ($wallet) {
            $audit = $wallet->shop->getFinancialAudit();

            // Attach audited values for the Blade view
            $wallet->audited_gross      = $audit['totalGross'];
            $wallet->commission_rate    = $audit['commissionRate'];
            $wallet->audited_commission = $audit['totalCommission'];
            $wallet->audited_net        = $audit['netEarnings'];
            $wallet->audited_locked     = $audit['pendingPayouts'];
            $wallet->audited_balance    = $audit['availableBalance'];

            return $wallet;
        });

        // 3. Optional: Pass the InTouch Float balance to the view for Admin awareness
        $floatBalance = $this->intouchService->getBalance()['balance'] ?? 0;

        return view('admin.wallets.index', compact('wallets', 'floatBalance'));
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
     * Uses a transaction and logs the admin responsible.
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

                // 1. Create the Transaction Record with a generated reference
                $reference = 'ADJ-' . strtoupper(bin2hex(random_bytes(3))) . '-' . time();

                $wallet->transactions()->create([
                    'type'           => $request->type,
                    'amount'         => $request->amount,
                    'description'    => $request->description,
                    'status'         => 'completed',
                    'reference_type' => 'AdminAdjustment',
                    'reference_id'   => auth()->id(), // Who did it?
                    'reference'      => $reference,   // Professional tracking ID
                ]);

                // 2. Update the Wallet Balance
                if ($request->type == 'credit') {
                    $wallet->increment('balance', $request->amount);
                } else {
                    // Safety Guard: Re-audit balance before debiting
                    $audit = $wallet->shop->getFinancialAudit();
                    if ($audit['availableBalance'] < $request->amount) {
                         throw new \Exception('Insufficient audited balance for this debit adjustment.');
                    }
                    $wallet->decrement('balance', $request->amount);
                }

                $wallet->update(['last_transaction_at' => now()]);

                Log::info("Wallet Adjusted by Admin ID " . auth()->id(), [
                    'shop_id' => $request->shop_id,
                    'type' => $request->type,
                    'amount' => $request->amount
                ]);

                return redirect()->route('admin.wallets.index')
                    ->with('success', "Wallet for {$wallet->shop->shop_name} adjusted successfully.");
            });
        } catch (\Exception $e) {
            Log::error("Wallet Adjustment Failed: " . $e->getMessage());
            return back()->with('error', 'Adjustment Error: ' . $e->getMessage())->withInput();
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

        // Fetch current Audit data for the "Show" page to ensure accuracy
        $audit = $wallet->shop->getFinancialAudit();

        return view('admin.wallets.show', compact('wallet', 'audit'));
    }

    /**
     * Update metadata (Immutable amount for audit integrity).
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['description' => 'required|string|max:255']);
        
        $transaction = WalletTransaction::findOrFail($id);
        
        // Prevent editing old transactions beyond a certain time if needed
        if ($transaction->created_at->diffInDays(now()) > 7) {
            return back()->with('error', 'Historical transaction descriptions cannot be changed.');
        }

        $transaction->update(['description' => $request->description]);

        return redirect()->back()->with('success', 'Transaction description updated.');
    }

    /**
     * Lock/Unlock Wallets instead of destroying them.
     */
    public function destroy(string $id)
    {
        return back()->with('error', 'Wallets are permanent audit records. Deactivate the Shop to freeze funds.');
    }
}