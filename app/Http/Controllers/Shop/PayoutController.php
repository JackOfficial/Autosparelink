<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\WalletTransaction; // Added
use App\Services\InTouchPaymentService;
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
        // This method in Shop.php now uses shop_payout and unit_price logic
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

        return DB::transaction(function () use ($request) {
            $shop = Auth::user()->shop;
            $summary = $this->getFinancialSummary();

            // Use the audited balance which accounts for the new commission markup logic
            if ($request->amount > $summary['availableBalance']) {
                return back()->with('error', 'Insufficient balance. Audited balance: ' . number_format($summary['availableBalance']) . ' RWF.');
            }

            // 1. Create the local Payout record (The "Request" log)
            $payout = $shop->payouts()->create([
                'amount'          => $request->amount,
                'payout_method'   => $request->payout_method,
                'account_details' => $request->account_details,
                'status'          => 'processing', 
                'currency'        => 'RWF',
                'reference'       => 'WD-' . strtoupper(bin2hex(random_bytes(4))) . '-' . time(),
            ]);

            try {
                // 2. Trigger the real money transfer via InTouch
                $response = $this->intouchService->requestDeposit(
                    $request->account_details,
                    $request->amount,
                    $payout->reference,
                    // FIXED: Corrected 'name' to 'shop_name'
                    "Withdrawal for " . $shop->shop_name 
                );

                // 3. Evaluate the Gateway Response
                if (isset($response['status']) && (strtolower($response['status']) == 'successfull' || $response['responsecode'] == '01')) {
                    
                    // 4. Record a Wallet Transaction to sync with your Wallet balance logic
                    // Your WalletTransaction::booted() logic will automatically decrement the wallet balance
                    $shop->wallet->transactions()->create([
                        'type'           => 'debit',
                        'amount'         => $request->amount,
                        'status'         => 'completed',
                        'reference_id'   => $payout->id,
                        'reference_type' => Payout::class,
                        'description'    => "Withdrawal to " . $request->account_details,
                    ]);

                    $payout->update([
                        'status' => 'completed',
                        'gateway_transaction_id' => $response['transactionid'] ?? null
                    ]);

                    return redirect()->route('shop.payouts.index')
                        ->with('success', 'Transfer successful! ' . number_format($request->amount) . ' RWF has been sent to your account.');
                } else {
                    throw new \Exception($response['statusdesc'] ?? 'InTouch Gateway rejected the transfer.');
                }

            } catch (\Exception $e) {
                Log::error("Payout Failed for Shop " . $shop->id . ": " . $e->getMessage());
                
                $payout->update([
                    'status' => 'failed', 
                    'error_log' => $e->getMessage()
                ]);
                
                return back()->with('error', 'Payment provider error: ' . $e->getMessage());
            }
        });
    }
}