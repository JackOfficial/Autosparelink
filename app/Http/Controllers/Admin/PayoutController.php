<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\OrderItem;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    /**
     * Display a listing of all payout requests.
     */
    public function index()
    {
        $payouts = Payout::with('shop.user')
            ->latest()
            ->paginate(15);

        return view('admin.payouts.index', compact('payouts'));
    }

    /**
     * Display the specified payout request with audited balance verification.
     */
    public function show(string $id)
    {
        $payout = Payout::with('shop')->findOrFail($id);
        $shop = $payout->shop;

        // --- Audited Balance Calculation ---
        $rate = (Commission::getRate() ?? 0) / 100;

        $totalGross = OrderItem::where('shop_id', $shop->id)
            ->where('status', 'completed')
            ->whereHas('order', fn($q) => $q->where('status', 'completed'))
            ->sum(DB::raw('unit_price * quantity')) ?? 0;

        $netEarnings = $totalGross * (1 - $rate);

        // Deductions including ALREADY completed payouts, 
        // but EXCLUDING the current pending request we are looking at.
        $otherDeductions = Payout::where('shop_id', $shop->id)
            ->where('id', '!=', $payout->id)
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->sum('amount') ?? 0;

        $actualAvailableBeforeThisPayout = $netEarnings - $otherDeductions;

        return view('admin.payouts.show', compact('payout', 'actualAvailableBeforeThisPayout'));
    }

    /**
     * Update the status of the payout (Approve/Process/Reject).
     */
    public function update(Request $request, string $id)
    {
        $payout = Payout::findOrFail($id);
        
        // 1. Safety Guard: Prevent modification of finalized payouts
        if (in_array($payout->status, ['completed', 'rejected'])) {
            return back()->with('error', 'This payout has already been finalized and cannot be changed.');
        }

        // 2. Validation
        $request->validate([
            'status' => 'required|in:processing,completed,rejected',
            'admin_note' => $request->status == 'rejected' 
                            ? 'required|string|min:5|max:500' // Require reason for rejection
                            : 'nullable|string|max:500'
        ], [
            'admin_note.required' => 'Please provide a reason for rejecting this payout so the vendor understands why.'
        ]);

        // 3. Logic for Approval (Audit Check)
        if ($request->status == 'completed') {
            $rate = (Commission::getRate() ?? 0) / 100;
            
            $totalGross = OrderItem::where('shop_id', $payout->shop_id)
                ->where('status', 'completed')
                ->whereHas('order', fn($q) => $q->where('status', 'completed'))
                ->sum(DB::raw('unit_price * quantity')) ?? 0;

            $netEarnings = $totalGross * (1 - $rate);

            $otherDeductions = Payout::where('shop_id', $payout->shop_id)
                ->where('id', '!=', $payout->id)
                ->whereIn('status', ['completed', 'pending', 'processing'])
                ->sum('amount') ?? 0;

            $currentAvailable = $netEarnings - $otherDeductions;

            if ($payout->amount > $currentAvailable) {
                return back()->with('error', 'Insufficient audited funds. The shop balance might have changed due to recent refunds.');
            }
        }

        // 4. Update the record
        // By changing status to 'rejected', it automatically disappears from 
        // the 'otherDeductions' sum in the next calculation, effectively restoring the balance.
        $payout->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'processed_at' => in_array($request->status, ['completed', 'rejected']) ? now() : $payout->processed_at,
        ]);

        $message = $request->status == 'rejected' 
            ? "Payout has been rejected and funds have been released back to the vendor."
            : "Payout status updated to {$request->status}.";

        return redirect()->route('admin.payouts.index')->with('success', $message);
    }

    /**
     * Remove the specified resource (Soft delete or archive).
     */
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