<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Shop;
use App\Services\InTouchPaymentService;
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
     */
  /**
     * Display all shop wallets with their current balances.
     */
    public function index()
    {
        // Option A: Only load wallets that actually have an active shop
        $wallets = Wallet::has('shop')
            ->with('shop')
            ->latest('updated_at')
            ->paginate(15);

        $wallets->getCollection()->transform(function ($wallet) {
            // Option B: Extra defensive guard check just in case
            if (!$wallet->shop) {
                $wallet->audited_gross      = 0;
                $wallet->commission_rate    = 0;
                $wallet->audited_commission = 0;
                $wallet->audited_net        = 0;
                $wallet->audited_locked     = 0;
                $wallet->audited_balance    = 0;
                return $wallet;
            }

            $audit = $wallet->shop->getFinancialAudit();
            $wallet->audited_gross      = $audit['totalGross'];
            $wallet->commission_rate    = $audit['commissionRate'];
            $wallet->audited_commission = $audit['totalCommission'];
            $wallet->audited_net        = $audit['netEarnings'];
            $wallet->audited_locked     = $audit['pendingPayouts'];
            $wallet->audited_balance    = $audit['availableBalance'];
            return $wallet;
        });

        $floatBalance = $this->intouchService->getBalance()['balance'] ?? 0;

        return view('admin.wallets.index', compact('wallets', 'floatBalance'));
    }

    public function create()
    {
        $shops = Shop::where('is_verified', true)->pluck('shop_name', 'id');
        return view('admin.wallets.adjust', compact('shops'));
    }

    /**
     * Store manual adjustment and process payment via InTouch service.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shop_id'         => 'required|exists:shops,id',
            'type'            => 'required|in:credit,debit',
            'amount'          => 'required|numeric|min:1',
            'description'     => 'required|string|max:255',
            'payout_method'   => 'required_if:type,debit|string|nullable',
            'account_details' => 'required_if:type,debit|string|nullable', // Phone number for Intouch
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $wallet = Wallet::where('shop_id', $request->shop_id)->lockForUpdate()->first();
                
                if (!$wallet) {
                    $wallet = Wallet::create(['shop_id' => $request->shop_id, 'balance' => 0]);
                }

                $adjReference = 'ADJ-' . strtoupper(bin2hex(random_bytes(3))) . '-' . time();
                $payReference = 'WD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . time();
                $status = 'completed';

                // 1. Handle Debit (Manual Payout) via InTouch Service
                if ($request->type == 'debit') {
                    $audit = $wallet->shop->getFinancialAudit();
                    if ($audit['availableBalance'] < $request->amount) {
                         throw new \Exception('Insufficient available balance to perform this payout.');
                    }

                    // Create the Payout record first (Processing)
                    $payout = $wallet->shop->payouts()->create([
                        'amount'          => $request->amount,
                        'payout_method'   => $request->payout_method ?? 'Admin Disbursement',
                        'account_details' => $request->account_details ?? $wallet->shop->phone_number,
                        'status'          => 'processing',
                        'currency'        => 'RWF',
                        'reference'       => $payReference,
                    ]);

                    // Trigger the service call
                    $response = $this->intouchService->requestDeposit(
                        $request->account_details ?? $wallet->shop->phone_number,
                        $request->amount,
                        $payReference,
                        "Admin Payout: " . $request->description
                    );

                    // Evaluate gateway response using your service's logic
                    if (isset($response['status']) && (strtolower($response['status']) == 'successfull' || $response['responsecode'] == '01')) {
                        $wallet->decrement('balance', $request->amount);
                        
                        $payout->update([
                            'status' => 'completed',
                            'gateway_transaction_id' => $response['transactionid'] ?? null
                        ]);
                    } else {
                        $payout->update(['status' => 'failed', 'error_log' => $response['statusdesc'] ?? 'Gateway rejected transfer']);
                        throw new \Exception($response['statusdesc'] ?? 'InTouch Gateway rejected the transfer.');
                    }
                } else {
                    // 2. Handle Credit (Manual Addition)
                    $wallet->increment('balance', $request->amount);
                }

                // 3. Create the Ledger Entry
                $wallet->transactions()->create([
                    'type'           => $request->type,
                    'amount'         => $request->amount,
                    'description'    => $request->description,
                    'status'         => 'completed',
                    'reference_type' => 'Admin Adjustment',
                    'reference_id'   => auth()->id(), 
                    'reference'      => $adjReference,
                ]);

                $wallet->updateQuietly(['last_transaction_at' => now()]);

                return redirect()->route('admin.wallets.index')
                    ->with('success', "Adjustment of " . number_format($request->amount) . " RWF processed successfully.");
            });
        } catch (\Exception $e) {
            Log::error("Financial Adjustment Failed: " . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show(string $id)
    {
        $wallet = Wallet::with(['shop', 'transactions' => fn($q) => $q->latest()])->findOrFail($id);
        $audit = $wallet->shop->getFinancialAudit();
        return view('admin.wallets.show', compact('wallet', 'audit'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['description' => 'required|string|max:255']);
        $transaction = WalletTransaction::findOrFail($id);
        
        if ($transaction->created_at->diffInDays(now()) > 7) {
            return back()->with('error', 'Historical transaction descriptions cannot be changed.');
        }

        $transaction->update(['description' => $request->description]);
        return redirect()->back()->with('success', 'Transaction description updated.');
    }

    public function destroy(string $id)
    {
        return back()->with('error', 'Wallets are permanent audit records. Deactivate the Shop to freeze funds.');
    }
}