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
        // Validate by the explicitly created Payout model ID rather than loose parameters
        $request->validate([
            'payout_id' => 'required|exists:payouts,id'
        ]);

        // 1. Lock and evaluate the local model state before touching an external gateway API
        DB::beginTransaction();
        try {
            $payout = Payout::where('id', $request->payout_id)
                            ->lockForUpdate()
                            ->firstOrFail();

            // Fail-safe: stop processing if this payout was already handled
            if ($payout->status !== 'pending') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'This payout request has already been processed or is currently active.'
                ], 422);
            }

            // Transition status to processing to secure the row lock against concurrent attempts
            $payout->update([
                'status' => 'processing'
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payout state locking failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Database concurrency error.'], 500);
        }

        // 2. Perform the external gateway interaction safely outside the main state lock
        try {
            // Note: your Payout model's booted method auto-generates $payout->reference ('WD-...') on creation
            $response = $this->intouchService->requestDeposit(
                $payout->account_details, // The phone number stored in the Payout row
                $payout->amount,
                $payout->reference,
                "Vendor Payout for Shop #" . $payout->shop_id
            );

            Log::info('InTouch Deposit Response:', $response);

            $responseCode = $response['responsecode'] ?? null;
            $statusStr = strtolower($response['status'] ?? '');

            // 3. Evaluate the submission state
            if ($statusStr === 'successfull' || $responseCode === '01' || $responseCode === '00') {
                
                // Update local status safely. If InTouch processes this asynchronously,
                // set to 'processing' here and expect a webhook callback to set 'completed'.
                $payout->update([
                    'status' => 'completed', 
                    'gateway_transaction_id' => $response['transactionid'] ?? null,
                    'processed_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payout completed successfully',
                    'transaction_id' => $response['transactionid'] ?? null
                ]);
            }

            // Handle clean gateway rejections
            $payout->update([
                'status' => 'failed',
                'error_log' => json_encode($response)
            ]);

            return response()->json([
                'success' => false,
                'message' => $response['statusdesc'] ?? 'Payout rejected by gateway',
                'raw' => $response
            ], 400);

        } catch (\Exception $e) {
            Log::error('InTouch Deposit Error: ' . $e->getMessage());
            
            // Revert state back to pending if a network timeout occurs so it can be safely retried
            $payout->update([
                'status' => 'pending',
                'error_log' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A gateway connection error occurred. State reverted safely to pending.'
            ], 500);
        }
    }

    public function checkBalance()
    {
        // Make sure your administration middlewares protect this route context securely!
        $balanceData = $this->intouchService->getBalance();
        return response()->json($balanceData);
    }
}