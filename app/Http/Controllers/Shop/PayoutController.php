<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\WalletTransaction;
use App\Services\InTouchPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth, Log};

class PayoutController extends Controller
{
    protected $intouchService;

    public function __construct(InTouchPaymentService $intouchService)
    {
        $this->intouchService = $intouchService;
    }

    private function getFinancialSummary()
    {
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
            'amount' => 'required|integer|min:100',
            'payout_method' => 'required|string|in:MTN MoMo,Airtel Money', 
            'account_details' => 'required|string|max:255', 
        ]);

        $shop = Auth::user()->shop;
        $payout = null;
        $walletTransaction = null;

        // PHASE 1: Validate, Lock Wallet Row, and Create Pending Audit Records
        DB::beginTransaction();
        try {
            // lockForUpdate prevents concurrent requests from reading old balances
            $wallet = $shop->wallet()->lockForUpdate()->firstOrFail();
            
            if ($request->amount > $wallet->balance) {
                DB::rollBack();
                return back()->with('error', 'Insufficient balance. Available balance: ' . number_format($wallet->balance) . ' RWF.');
            }

            $referenceId = 'WD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . time();

            // 1. Log the payout request structure
            $payout = $shop->payouts()->create([
                'amount'          => $request->amount,
                'payout_method'   => $request->payout_method,
                'account_details' => $request->account_details,
                'status'          => 'processing', 
                'currency'        => 'RWF',
                'reference'       => $referenceId,
            ]);

            // 2. Log underlying transaction—observer moves funds from balance to pending_balance
            $walletTransaction = $wallet->transactions()->create([
                'type'           => 'debit',
                'amount'         => $request->amount,
                'service_fee'    => 0,
                'fee_percentage' => 0,
                'reference_type' => Payout::class,
                'reference_id'   => $payout->id,
                'description'    => "Withdrawal via {$request->payout_method} to {$request->account_details}",
                'status'         => 'pending',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Payout initialization failed for Shop {$shop->id}: " . $e->getMessage());
            return back()->with('error', 'Could not process withdrawal request. Please retry.');
        }

        // PHASE 2: External Gateway API Call (Safe from DB Transaction lockups)
        try {
            $response = $this->intouchService->requestDeposit(
                $request->account_details,
                $request->amount,
                $payout->reference,
                "Withdrawal for " . $shop->shop_name 
            );

            $responseCode = $response['responsecode'] ?? null;
            $statusStr = strtolower($response['status'] ?? '');

            // Handshake Accepted successfully by provider network (handling typo variants like 'successful' or 'successfull')
            if (str_contains($statusStr, 'success') || $statusStr === 'pending' || $responseCode === '01' || $responseCode === '00') {
                
                // Individual instance update fires the necessary model observers cleanly
                $payout->update([
                    'gateway_transaction_id' => $response['transactionid'] ?? null
                ]);

                return redirect()->route('shop.payouts.index')
                    ->with('success', 'Withdrawal request initiated successfully. Funds will appear once settled by the network.');
            }

            // Gateway rejected it immediately
            $payout->update([
                'status' => 'failed',
                'error_log' => $response['statusdesc'] ?? 'Gateway rejected parameters.'
            ]);

            // Triggers observer to safely reverse pending_balance back to balance
            $walletTransaction->update(['status' => 'failed']);

            return back()->with('error', 'Payment provider rejected request: ' . ($response['statusdesc'] ?? 'Unknown Error'));

        } catch (\Exception $e) {
            Log::critical("Payout Gateway Timeout or Critical Error for Payout ID {$payout->id}: " . $e->getMessage());
            
            // Do NOT touch the wallet transaction status here. Leave it 'pending'.
            // Webhooks or background jobs will sweep up and reconcile this later.
            $payout->update([
                'status' => 'processing',
                'error_log' => 'Timeout encountered. Verification pending structural audit. ' . $e->getMessage()
            ]);
            
            return redirect()->route('shop.payouts.index')
                ->with('warning', 'Gateway transaction is processing. We are verifying the final state with the network.');
        }
    }
}