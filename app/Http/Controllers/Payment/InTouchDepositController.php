<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\InTouchPaymentService;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log};

class InTouchDepositController extends Controller
{
    protected $intouchService;

    public function __construct(InTouchPaymentService $intouchService)
    {
        $this->intouchService = $intouchService;
    }

    /**
     * Trigger an administrative payout release safely
     */
    public function processPayout(Request $request)
    {
        $request->validate([
            'payout_id' => 'required|exists:payouts,id'
        ]);

        // 1. Lock and evaluate local model state within a micro-transaction
        DB::beginTransaction();
        try {
            $payout = Payout::where('id', $request->payout_id)
                            ->lockForUpdate()
                            ->firstOrFail();

            // Fail-safe: Stop dead if this row is not cleanly pending
            if ($payout->status !== 'pending') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'This payout request has already been processed or is currently active.'
                ], 422);
            }

            // Lock the status to 'processing' before exposing to the network
            $payout->update([
                'status' => 'processing'
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payout state locking failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Database concurrency error.'], 500);
        }

        // 2. Perform external gateway interaction safely outside the database lock
        try {
            $response = $this->intouchService->requestDeposit(
                $payout->account_details, 
                $payout->amount,
                $payout->reference,
                "Vendor Payout for Shop #" . $payout->shop_id
            );

            Log::info('InTouch Deposit Response:', $response);

            $responseCode = $response['responsecode'] ?? null;
            $statusStr = strtolower($response['status'] ?? '');

            // 3. Evaluate the definitive submission state
            // NOTE: Double-check if your gateway provider explicitly spells it 'successfull' with two 'l's
            if ($statusStr === 'successfull' || $statusStr === 'successful' || $responseCode === '01' || $responseCode === '00') {
                
                $payout->update([
                    'status' => 'completed', 
                    'gateway_transaction_id' => $response['transactionid'] ?? null,
                    'processed_at' => now()
                ]);

                // NOTE: If you are not using a PayoutObserver to handle wallet balances,
                // you should explicitly trigger your WalletTransaction debit ledger creation here.

                return response()->json([
                    'success' => true,
                    'message' => 'Payout completed successfully',
                    'transaction_id' => $response['transactionid'] ?? null
                ]);
            }

            // Handle explicit API rejections cleanly (e.g., Insufficient Gateway Balance, Invalid Number)
            $payout->update([
                'status' => 'failed',
                'error_log' => json_encode($response)
            ]);

            return response()->json([
                'success' => false,
                'message' => $response['statusdesc'] ?? 'Payout rejected by gateway.',
                'raw' => $response
            ], 400);

        } catch (\Exception $e) {
            Log::critical("InTouch Network Timeout or Critical Error for Payout #{$payout->id}: " . $e->getMessage());
            
            // SECURITY FIX: Leave status as 'processing' or flip to an 'indeterminate' state.
            // NEVER automatically set it back to 'pending' during a network failure.
            $payout->update([
                'status' => 'processing', 
                'error_log' => 'Network timeout/disconnection: Status unverified on gateway. ' . $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A gateway connection error occurred. Handshake status is unverified; transaction locked to prevent duplicate charges.'
            ], 500);
        }
    }

    public function checkBalance()
    {
        $balanceData = $this->intouchService->getBalance();
        return response()->json($balanceData);
    }
}