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
        
        $request->validate([
            'status' => 'required|in:processing,completed,rejected',
            'admin_note' => 'nullable|string|max:500'
        ]);

        // If approving, perform one last audit check
        if ($request->status === 'completed' || $request->status === 'processing') {
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
                return back()->with('error', 'Insufficient audited funds to complete this payout.');
            }
        }

        $payout->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'processed_at' => $request->status === 'completed' ? now() : $payout->processed_at,
        ]);

        return redirect()->route('admin.payouts.index')
            ->with('success', "Payout status updated to {$request->status}.");
    }

    /**
     * Remove the specified resource (Soft delete or archive).
     */
    public function destroy(string $id)
    {
        $payout = Payout::findOrFail($id);
        
        if ($payout->status === 'pending') {
            $payout->delete();
            return back()->with('success', 'Payout request cancelled.');
        }

        return back()->with('error', 'Only pending requests can be deleted.');
    }
}