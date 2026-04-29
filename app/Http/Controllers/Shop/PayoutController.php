<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Services\InTouchPaymentService; // Import the Service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    /**
     * Store and AUTOMATICALLY process the payout via InTouch
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100', // Reduced min for testing if needed
            'payout_method' => 'required|string|in:MTN MoMo,Airtel Money', 
            'account_details' => 'required|string|max:255', // This should be the phone number
        ]);

        return DB::transaction(function () use ($request) {
            $summary = $this->getFinancialSummary();

            if ($request->amount > $summary['availableBalance']) {
                return back()->with('error', 'Insufficient balance. Audited balance: ' . number_format($summary['availableBalance']) . ' RWF.');
            }

            // 1. Create the local Payout record first (Locks the funds)
            $payout = Auth::user()->shop->payouts()->create([
                'amount'          => $request->amount,
                'payout_method'   => $request->payout_method,
                'account_details' => $request->account_details,
                'status'          => 'processing', // Start as processing
                'currency'        => 'RWF',
                'reference'       => 'WD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . time(),
            ]);

            try {
                // 2. Trigger the real money transfer via InTouch
                $response = $this->intouchService->requestDeposit(
                    $request->account_details, // The phone number
                    $request->amount,
                    $payout->reference,
                    "Withdrawal for " . Auth::user()->shop->name
                );

                // 3. Evaluate the Gateway Response
                // Note: Check for 'Successfull' (two l's) as seen on your Beeceptor test
                if (isset($response['status']) && (strtolower($response['status']) == 'successfull' || $response['responsecode'] == '01')) {
                    
                    $payout->update([
                        'status' => 'completed',
                        'gateway_transaction_id' => $response['transactionid'] ?? null
                    ]);

                    return redirect()->route('shop.payouts.index')
                        ->with('success', 'Transfer successful! ' . number_format($request->amount) . ' RWF has been sent to your wallet.');
                } else {
                    // Gateway rejected it (e.g. invalid number or gateway down)
                    throw new \Exception($response['statusdesc'] ?? 'InTouch Gateway rejected the transfer.');
                }

            } catch (\Exception $e) {
                // Rollback happens automatically here if we throw an exception
                Log::error("Payout Failed for Shop " . Auth::user()->shop->id . ": " . $e->getMessage());
                
                // We update the record to 'failed' instead of deleting so the audit trail is clear
                $payout->update(['status' => 'failed', 'error_log' => $e->getMessage()]);
                
                return back()->with('error', 'Payment provider error: ' . $e->getMessage());
            }
        });
    }
}