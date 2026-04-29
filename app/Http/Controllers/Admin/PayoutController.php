<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Payout, OrderItem, Commission};
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
        $payout = Payout::with('shop')->findOrFail($id);
        $shop = $payout->shop;

        // Financial Audit Logic (Calculates net from completed orders minus other payouts)
        $rate = (Commission::getRate() ?? 0) / 100;

        $totalGross = OrderItem::where('shop_id', $shop->id)
            ->where('status', 'completed')
            ->whereHas('order', fn($q) => $q->where('status', 'completed'))
            ->sum(DB::raw('unit_price * quantity')) ?? 0;

        $netEarnings = $totalGross * (1 - $rate);

        $otherDeductions = Payout::where('shop_id', $shop->id)
            ->where('id', '!=', $payout->id)
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->sum('amount') ?? 0;

        $actualAvailableBeforeThisPayout = $netEarnings - $otherDeductions;

        return view('admin.payouts.show', compact('payout', 'actualAvailableBeforeThisPayout'));
    }

    /**
     * Admin modification of Payout Status.
     * If moved to 'completed' here, it triggers the InTouch API.
     */
    public function update(Request $request, string $id)
    {
        $payout = Payout::findOrFail($id);
        
        if (in_array($payout->status, ['completed', 'rejected'])) {
            return back()->with('error', 'This payout has already been finalized.');
        }

        $request->validate([
            'status' => 'required|in:processing,completed,rejected',
            'admin_note' => $request->status == 'rejected' ? 'required|string|min:5' : 'nullable|string'
        ]);

        // If Admin is marking as COMPLETED, we attempt the real-time transfer
        if ($request->status == 'completed') {
            
            // 1. Final Audit Check
            $rate = (Commission::getRate() ?? 0) / 100;
            $totalGross = OrderItem::where('shop_id', $payout->shop_id)
                ->where('status', 'completed')
                ->whereHas('order', fn($q) => $q->where('status', 'completed'))
                ->sum(DB::raw('unit_price * quantity')) ?? 0;

            $currentAvailable = ($totalGross * (1 - $rate)) - 
                Payout::where('shop_id', $payout->shop_id)
                    ->where('id', '!=', $payout->id)
                    ->whereIn('status', ['completed', 'pending', 'processing'])
                    ->sum('amount');

            if ($payout->amount > $currentAvailable) {
                return back()->with('error', 'Insufficient audited funds to complete this transfer.');
            }

            // 2. API Disbursement Attempt
            try {
                // Ensure a reference exists
                $reference = $payout->reference ?? 'ADM-WD-' . time();

                $response = $this->intouchService->requestDeposit(
                    $payout->account_details, // The vendor's phone
                    $payout->amount,
                    $reference,
                    "Admin Approved Payout: " . $payout->shop->name
                );

                // Check InTouch response (handling the 'Successfull' typo)
                if (isset($response['status']) && (strtolower($response['status']) == 'successfull' || $response['responsecode'] == '01')) {
                    $payout->update([
                        'status' => 'completed',
                        'admin_note' => $request->admin_note,
                        'processed_at' => now(),
                        'gateway_transaction_id' => $response['transactionid'] ?? null,
                        'reference' => $reference
                    ]);
                    
                    return redirect()->route('admin.payouts.index')->with('success', 'Payout successfully disbursed via InTouch.');
                } else {
                    Log::error("Admin Payout API Failure:", $response);
                    return back()->with('error', 'InTouch Gateway Error: ' . ($response['statusdesc'] ?? 'Unknown Error'));
                }

            } catch (\Exception $e) {
                Log::error("Admin Payout Exception: " . $e->getMessage());
                return back()->with('error', 'System Error: ' . $e->getMessage());
            }
        }

        // Standard update for Rejection or Processing status
        $payout->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'processed_at' => $request->status == 'rejected' ? now() : $payout->processed_at,
        ]);

        return redirect()->route('admin.payouts.index')->with('success', "Payout status updated to {$request->status}.");
    }

    public function destroy(string $id)
    {
        $payout = Payout::findOrFail($id);
        if ($payout->status == 'pending') {
            $payout->delete();
            return back()->with('success', 'Payout request cancelled.');
        }
        return back()->with('error', 'Only pending requests can be deleted.');
    }
}