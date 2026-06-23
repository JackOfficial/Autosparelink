<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Services\InTouchPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log};

class PayoutController extends Controller
{
    protected $intouchService;

    public function __construct(InTouchPaymentService $intouchService)
    {
        $this->intouchService = $intouchService;
    }

    public function index()
    {
        $payouts = Payout::with('shop.user')
            ->latest()
            ->paginate(15);

        return view('admin.payouts.index', compact('payouts'));
    }

    public function show(string $id)
    {
        $payout = Payout::with('shop.wallet')->findOrFail($id);
        
        // Single point of truth math from our Shop model profile
        $audit = $payout->shop->getFinancialAudit();

        // If the current request is already processing/pending, add it back to show what the pool looked like
        $isLocked = in_array($payout->status, ['pending', 'processing']);
        $actualAvailableBeforeThisPayout = $audit['availableBalance'] + ($isLocked ? $payout->amount : 0);

        return view('admin.payouts.show', compact('payout', 'actualAvailableBeforeThisPayout'));
    }

    /**
     * Safely process payout transitions with proper ledger synchronization
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:processing,completed,rejected',
            'admin_note' => $request->status === 'rejected' ? 'required|string|min:5' : 'nullable|string'
        ]);

        return DB::transaction(function () use ($request, $id) {
            // 1. Lock rows defensively to block concurrent modifications/webhooks
            $payout = Payout::where('id', $id)->lockForUpdate()->firstOrFail();
            $shop = $payout->shop;
            $wallet = $shop->wallet()->lockForUpdate()->firstOrFail();
            
            if (in_array($payout->status, ['completed', 'rejected'])) {
                return back()->with('error', 'This payout has already been finalized.');
            }

            // ACTION: ADMIN MARKS AS COMPLETED (Triggers Gateway)
            if ($request->status === 'completed') {
                $audit = $shop->getFinancialAudit();

                // Double check that the vendor actually has these funds available
                if ($payout->amount > ($audit['availableBalance'] + $payout->amount)) {
                    return back()->with('error', 'Overdraft Guard: Vendor does not have enough verified funds.');
                }

                try {
                    $reference = $payout->reference ?? 'ADM-WD-' . time();

                    $response = $this->intouchService->requestDeposit(
                        $payout->account_details,
                        $payout->amount,
                        $reference,
                        "Payout to " . $shop->shop_name
                    );

                    if (isset($response['status']) && (strtolower($response['status']) === 'successfull' || $response['responsecode'] === '01')) {
                        
                        // Deduct money from persistent storage wallet row
                        $wallet->decrement('balance', $payout->amount);

                        $payout->update([
                            'status' => 'completed',
                            'admin_note' => $request->admin_note,
                            'processed_at' => now(),
                            'gateway_transaction_id' => $response['transactionid'] ?? null,
                            'reference' => $reference
                        ]);
                        
                        return redirect()->route('admin.payouts.index')->with('success', 'Payout cleared and disbursed successfully.');
                    }

                    Log::error("InTouch API Disburse Rejection:", $response);
                    return back()->with('error', 'Gateway Error: ' . ($response['statusdesc'] ?? 'Disbursement refused.'));

                } catch (\Exception $e) {
                    Log::error("Disbursement Thread Panic Exception: " . $e->getMessage());
                    return back()->with('error', 'Transport error running gateway handshake: ' . $e->getMessage());
                }
            }

            // ACTION: ADMIN MARKS AS REJECTED
            if ($request->status === 'rejected') {
                // Moving status to rejected automatically drops it from 'pendingPayouts' list,
                // making it instantly spendable again on the dashboard through getFinancialAudit()
                $payout->update([
                    'status' => 'rejected',
                    'admin_note' => $request->admin_note,
                    'processed_at' => now()
                ]);

                return redirect()->route('admin.payouts.index')->with('success', 'Payout rejected. Funds unlocked back into available balance.');
            }

            // Standard fallback state change (e.g., pending -> processing)
            $payout->update([
                'status' => $request->status,
                'admin_note' => $request->admin_note
            ]);

            return redirect()->route('admin.payouts.index')->with('success', "Payout status updated to {$request->status}.");
        });
    }

    /**
     * Block hard deletions of payout log history entries
     */
    public function destroy(string $id)
    {
        return back()->with('error', 'Financial records cannot be hard deleted. Reject the request to free up balances instead.');
    }
}