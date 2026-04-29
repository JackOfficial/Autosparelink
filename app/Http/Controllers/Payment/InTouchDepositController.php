<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\InTouchPaymentService;
use App\Models\Order; // Or a Withdrawal/Payout model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InTouchDepositController extends Controller
{
    protected $intouchService;

    public function __construct(InTouchPaymentService $intouchService)
    {
        $this->intouchService = $intouchService;
    }

    /**
     * Trigger a payout to a vendor/user
     */
    public function processPayout(Request $request)
    {
        // 1. Validation (Ensure you have a phone and amount)
        $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:100',
            'order_id' => 'nullable|exists:orders,id'
        ]);

        // 2. Generate a unique Transaction ID for this payout
        // Use a prefix like 'WD-' (Withdrawal) to distinguish from payments
        $requestId = 'WD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . time();

        try {
            // 3. Call the Service
            $response = $this->intouchService->requestDeposit(
                $request->phone,
                $request->amount,
                $requestId,
                "Vendor Payout for Order #" . ($request->order_id ?? 'N/A')
            );

            Log::info('InTouch Deposit Response:', $response);

            // 4. Handle InTouch Response
            // Usually, '00' or '01' indicates success/initiated
            if (isset($response['status']) && (strtolower($response['status']) == 'successfull' || $response['responsecode'] == '01')) {
                
                // Update your local database here (e.g., mark withdrawal as completed)
                // Withdrawal::where('id', ...)->update(['status' => 'completed']);

                return response()->json([
                    'success' => true,
                    'message' => 'Payout initiated successfully',
                    'transaction_id' => $response['transactionid'] ?? null
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['statusdesc'] ?? 'Payout failed at gateway',
                'raw' => $response
            ], 400);

        } catch (\Exception $e) {
            Log::error('InTouch Deposit Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An internal error occurred while processing the payout.'
            ], 500);
        }
    }

    public function checkBalance()
{
    // Ensure only Admins can see this!
    // $this->authorize('admin-only'); 

    $balanceData = $this->intouchService->getBalance();

    return response()->json($balanceData);
}

}