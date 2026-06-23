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
    public function index()
    {
        $wallets = Wallet::has('shop')
            ->with(['shop.orderItems', 'shop.payouts']) // Eager load to avoid N+1 query loops
            ->latest('updated_at')
            ->paginate(15);

        $wallets->getCollection()->transform(function ($wallet) {
            $audit = $wallet->shop->getFinancialAudit();
            $wallet->audited_gross      = $audit['totalGross'];
            $wallet->commission_rate    = $audit['commissionRate'];
            $wallet->audited_commission = $audit['totalCommission'];
            $wallet->audited_net        = $audit['netEarnings'];
            $wallet->audited_locked     = $audit['pendingPayouts'];
            $wallet->audited_balance    = $audit['availableBalance'];
            return $wallet;
        });

        // Safe fallback check if the gateway is unreachable
        try {
            $floatBalance = $this->intouchService->getBalance()['balance'] ?? 0;
        } catch (\Exception $e) {
            Log::warning("InTouch balance check failed on admin view: " . $e->getMessage());
            $floatBalance = 'Unavailable';
        }

        return view('admin.wallets.index', compact('wallets', 'floatBalance'));
    }

    public function create()
    {
        $shops = Shop::where('is_verified', true)->pluck('shop_name', 'id');
        return view('admin.wallets.adjust', compact('shops'));
    }

    /**
     * Store manual adjustment safely using outside-in network handling logic
     */
    public function store(Request $request)
    {
        $request->validate([
            'shop_id'         => 'required|exists:shops,id',
            'type'            => 'required|in:credit,debit',
            'amount'          => 'required|numeric|min:1',
            'description'     => 'required|string|max:255',
            'payout_method'   => 'required_if:type,debit|string|nullable',
            'account_details' => 'required_if:type,debit|string|nullable',
        ]);

        $shop = Shop::findOrFail($request->shop_id);
        $payReference = 'WD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . time();
        $adjReference = 'ADJ-' . strtoupper(bin2hex(random_bytes(3))) . '-' . time();
        $gatewayTransactionId = null;

        // --- PRE-CHECK AND NETWORK API PROCESSING (OUTSIDE TRANSACTION) ---
        if ($request->type === 'debit') {
            $audit = $shop->getFinancialAudit();
            if ($audit['availableBalance'] < $request->amount) {
                return back()->with('error', 'Insufficient available balance to perform this payout.')->withInput();
            }

            try {
                $response = $this->intouchService->requestDeposit(
                    $request->account_details ?? $shop->phone_number,
                    $request->amount,
                    $payReference,
                    "Admin Wallet Adjustment: " . $request->description
                );

                // Handle common InTouch success payloads safely
                if (isset($response['status']) && (strtolower($response['status']) === 'successfull' || $response['responsecode'] === '01')) {
                    $gatewayTransactionId = $response['transactionid'] ?? null;
                } else {
                    return back()->with('error', 'InTouch Gateway Rejected Transfer: ' . ($response['statusdesc'] ?? 'Unknown Reason'))->withInput();
                }
            } catch (\Exception $e) {
                Log::error("Network Timeout or Failure on Admin Wallet Adjust: " . $e->getMessage());
                return back()->with('error', 'Network Connection Timeout with InTouch. No money moved.')->withInput();
            }
        }

        // --- DATABASE OPERATIONS (SHORT, ATOMIC MUTATION BLOCK) ---
        try {
            DB::transaction(function () use ($request, $shop, $payReference, $adjReference, $gatewayTransactionId) {
                $wallet = Wallet::where('shop_id', $shop->id)->lockForUpdate()->firstOrCreate(
                    ['shop_id' => $shop->id],
                    ['balance' => 0]
                );

                if ($request->type === 'debit') {
                    // 1. Log the corresponding complete payout record
                    $shop->payouts()->create([
                        'amount'                 => $request->amount,
                        'payout_method'          => $request->payout_method ?? 'Admin Disbursement',
                        'account_details'        => $request->account_details ?? $shop->phone_number,
                        'status'                 => 'completed',
                        'currency'               => 'RWF',
                        'reference'              => $payReference,
                        'gateway_transaction_id' => $gatewayTransactionId,
                        'processed_at'           => now(),
                    ]);

                    $wallet->decrement('balance', $request->amount);
                } else {
                    // Credit operations run purely on the database
                    $wallet->increment('balance', $request->amount);
                }

                // 2. Log general Ledger Transaction Record
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
            });

            return redirect()->route('admin.wallets.index')
                ->with('success', "Adjustment of " . number_format($request->amount) . " RWF completed successfully.");

        } catch (\Exception $e) {
            Log::critical("CRITICAL LEDGER DESYNC FAILURE: Money moved via gateway, database failed to log details: " . $e->getMessage());
            return redirect()->route('admin.wallets.index')
                ->with('error', 'The money was moved via the gateway, but a system exception occurred while creating the transaction log. Please review logs immediately.');
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