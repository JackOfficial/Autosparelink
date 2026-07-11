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

        // PHASE 1: Validate, Lock Wallet Row, and Create Completed Ledger Records
        DB::beginTransaction();
        try {
            // lockForUpdate prevents concurrent requests from reading old balances
            $wallet = $shop->wallet()->lockForUpdate()->firstOrFail();
            
            if ($request->amount > $wallet->balance) {
                DB::rollBack();
                return back()->with('error', 'Insufficient balance. Available balance: ' . number_format($wallet->balance) . ' RWF.');
            }

            $referenceId = 'WD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . time();

            // 1. Log the payout request structure as completed immediately
            $payout = $shop->payouts()->create([
                'amount'          => $request->amount,
                'payout_method'   => $request->payout_method,
                'account_details' => $request->account_details,
                'status'          => 'completed', 
                'currency'        => 'RWF',
                'reference'       => $referenceId,
            ]);

            // 2. Created as completed -> triggers the model event to instantly decrement real balance
            $walletTransaction = $wallet->transactions()->create([
                'type'           => 'debit',
                'amount'         => $request->amount,
                'service_fee'    => 0,
                'fee_percentage' => 0,
                'reference_type' => Payout::class,
                'reference_id'   => $payout->id,
                'description'    => "Withdrawal via {$request->payout_method} to {$request->account_details}",
                'status'         => 'completed',
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

            // Gateway accepted and funds dispatched successfully
            if (str_contains($statusStr, 'success') || $statusStr === 'pending' || $responseCode === '01' || $responseCode === '00') {
                
                // Save gateway tracking ID cleanly
                $payout->update([
                    'gateway_transaction_id' => $response['transactionid'] ?? null
                ]);

                return redirect()->route('shop.payouts.index')
                    ->with('success', 'Withdrawal processed successfully! The funds have been transferred to your mobile money account.');
            }

            // Gateway rejected it immediately -> throw exception to handle atomic reversal
            throw new \Exception($response['statusdesc'] ?? 'Gateway rejected parameters.');

        } catch (\Exception $e) {
            Log::critical("Payout Gateway Failure for Payout ID {$payout->id}: " . $e->getMessage());
            
            // Mark payout record as failed
            $payout->update([
                'status' => 'failed',
                'error_log' => $e->getMessage()
            ]);

            // Refund the wallet balance directly since it was already deducted during Phase 1
            DB::transaction(function () use ($walletTransaction, $shop) {
                $walletTransaction->update(['status' => 'failed']);
                
                // Manually increment active balance back since the model event was skipped
                $shop->wallet()->increment('balance', $walletTransaction->amount);
            });

            return redirect()->route('shop.payouts.index')
                ->with('error', 'Payment transfer failed: ' . $e->getMessage() . '. Your balance has been restored.');
        }
    }
}