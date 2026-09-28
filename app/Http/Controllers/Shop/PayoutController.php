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
    protected const WITHDRAWAL_FEE = 300; // Fixed fee in RWF

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

        $shop = auth()->user()->shop;    

        return view('shop.payouts.index', array_merge($summary, [
            'payouts' => $payouts,
            'shop' => $shop
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

        $requestedAmount = (float) $request->amount;
        $fee = self::WITHDRAWAL_FEE;
        $totalDeduction = $requestedAmount + $fee;

        // PHASE 1: Validate, Lock Wallet Row, and Create Completed Ledger Records
        DB::beginTransaction();
        try {
            // lockForUpdate prevents concurrent requests from reading old balances
            $wallet = $shop->wallet()->lockForUpdate()->firstOrFail();
            
            // Validate balance against requested amount + 300 RWF fee
            if ($totalDeduction > $wallet->balance) {
                DB::rollBack();
                return back()->with('error', 'Insufficient balance. You need ' . number_format($totalDeduction) . ' RWF (including ' . number_format($fee) . ' RWF service fee). Available balance: ' . number_format($wallet->balance) . ' RWF.');
            }

            $referenceId = 'WD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . time();

            // 1. Log the payout record (net amount requested by seller)
            $payout = $shop->payouts()->create([
                'amount'          => $requestedAmount,
                'payout_method'   => $request->payout_method,
                'account_details' => $request->account_details,
                'status'          => 'completed', 
                'currency'        => 'RWF',
                'reference'       => $referenceId,
            ]);

            // 2. Created as completed -> model event decrements totalDeduction ($requestedAmount + $fee) from balance
            $walletTransaction = $wallet->transactions()->create([
                'type'           => 'debit',
                'amount'         => $totalDeduction,
                'service_fee'    => $fee,
                'fee_percentage' => 0,
                'reference_type' => Payout::class,
                'reference_id'   => $payout->id,
                'description'    => "Withdrawal of " . number_format($requestedAmount) . " RWF via {$request->payout_method} to {$request->account_details} (Fee: {$fee} RWF)",
                'status'         => 'completed',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Payout initialization failed for Shop {$shop->id}: " . $e->getMessage());
            return back()->with('error', 'Could not process withdrawal request. Please retry.');
        }

        // PHASE 2: External Gateway API Call (Transfer ONLY the net requested amount to vendor)
        try {
            $response = $this->intouchService->requestDeposit(
                $request->account_details,
                $requestedAmount,
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
                    ->with('success', 'Withdrawal processed successfully! ' . number_format($requestedAmount) . ' RWF has been transferred to your account.');
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

            // Refund full deducted amount ($requestedAmount + $fee) back to active balance
            DB::transaction(function () use ($walletTransaction, $shop) {
                $walletTransaction->update(['status' => 'failed']);
                
                // Increment active balance back by full transaction amount (includes fee)
                $shop->wallet()->increment('balance', $walletTransaction->amount);
            });

            return redirect()->route('shop.payouts.index')
                ->with('error', 'Payment transfer failed: ' . $e->getMessage() . '. Your balance has been fully restored.');
        }
    }
}