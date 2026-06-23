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

        // PHASE 1: Verify Balance and Secure the Intent Record (Short DB Lock)
        DB::beginTransaction();
        try {
            // Lock the wallet row to prevent concurrent processing requests
            $wallet = $shop->wallet()->lockForUpdate()->firstOrFail();
            $summary = $this->getFinancialSummary();

            if ($request->amount > $summary['availableBalance']) {
                DB::rollBack();
                return back()->with('error', 'Insufficient balance. Audited balance: ' . number_format($summary['availableBalance']) . ' RWF.');
            }

            // Create the record strictly as 'processing'
            $payout = $shop->payouts()->create([
                'amount'          => $request->amount,
                'payout_method'   => $request->payout_method,
                'account_details' => $request->account_details,
                'status'          => 'processing', 
                'currency'        => 'RWF',
                'reference'       => 'WD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . time(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Payout initialization failed for Shop {$shop->id}: " . $e->getMessage());
            return back()->with('error', 'Could not process withdrawal request. Please retry.');
        }

        // PHASE 2: External Gateway API Call (Completely outside DB Transaction Window)
        try {
            $response = $this->intouchService->requestDeposit(
                $request->account_details,
                $request->amount,
                $payout->reference,
                "Withdrawal for " . $shop->shop_name 
            );

            $responseCode = $response['responsecode'] ?? null;
            $statusStr = strtolower($response['status'] ?? '');

            // Check if submission was successfully received/accepted by the gateway
            if ($statusStr === 'successfull' || $responseCode === '01' || $responseCode === '00') {
                
                // Keep status as processing. Save the transaction reference for callback verification.
                $payout->update([
                    'gateway_transaction_id' => $response['transactionid'] ?? null
                ]);

                return redirect()->route('shop.payouts.index')
                    ->with('success', 'Withdrawal request initiated successfully. Funds will appear once settled by the network.');
            }

            // Gateway explicitly rejected the transaction on handshake
            $payout->update([
                'status' => 'failed',
                'error_log' => $response['statusdesc'] ?? 'Gateway rejected submission parameters.'
            ]);

            return back()->with('error', 'Payment provider rejected request: ' . ($response['statusdesc'] ?? 'Unknown Gateway Error'));

        } catch (\Exception $e) {
            Log::error("Payout Gateway Communication Failure for Payout #{$payout->id}: " . $e->getMessage());
            
            // Revert state back to pending so that it isn't permanently locked or lost due to a timeout
            $payout->update([
                'status' => 'pending',
                'error_log' => 'Network/Connection Timeout: ' . $e->getMessage()
            ]);
            
            return back()->with('error', 'Gateway communication timeout. Your balance is safe; please check your history or retry shortly.');
        }
    }
}