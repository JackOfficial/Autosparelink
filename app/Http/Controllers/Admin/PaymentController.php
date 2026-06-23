<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('order.user')
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(string $id)
    {
        $payment = Payment::with(['order.user', 'order.orderItems.part'])->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        $statuses = ['pending', 'processing', 'successful', 'failed', 'refunded'];

        return view('admin.payments.edit', compact('payment', 'statuses'));
    }

    /**
     * Update payment status and safely sync down to the child Order Items
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,successful,failed,refunded'
        ]);

        return DB::transaction(function () use ($request, $id) {
            // Lock the payment row to prevent webhook collision issues
            $payment = Payment::where('id', $id)->lockForUpdate()->firstOrFail();
            $oldStatus = $payment->status;

            if ($oldStatus == $request->status) {
                return redirect()->route('admin.payments.show', $payment->id);
            }

            // Update the payment status safely
            $payment->update([
                'status' => $request->status
            ]);

            $order = $payment->order;

            // STRATEGIC SYNC LOGIC: Handle state transitions cleanly
            if ($request->status == 'successful') {
                
                // 1. Move parent order forward out of pending status
                if ($order->status == 'pending') {
                    $order->update(['status' => 'processing']);
                }

                // 2. Safely cycle through items individually to guarantee observer evaluation
                foreach ($order->orderItems as $item) {
                    if ($item->status == 'pending') {
                        $item->status = 'completed'; // Switches status directly to run your wallet payouts logic
                        $item->save(); // Save individually on the instance to fire OrderItemObserver safely
                    }
                }
            } elseif (in_array($request->status, ['failed', 'refunded'])) {
                // If payment falls through or gets revoked, reflect that state downward cleanly
                if ($order->status == 'processing' || $order->status == 'pending') {
                    $order->update(['status' => $request->status]);
                }
                
                foreach ($order->orderItems as $item) {
                    if ($item->status !== 'completed') {
                        $item->status = 'failed';
                        $item->save();
                    }
                }
            }

            Log::info("Admin manually changed Payment #{$payment->id} state from '{$oldStatus}' to '{$request->status}'");

            return redirect()
                ->route('admin.payments.show', $payment->id)
                ->with('success', "Payment record successfully shifted to " . ucfirst($request->status));
        });
    }

    /**
     * Prevent hard destruction of permanent financial records
     */
    public function destroy(string $id)
    {
        // Block actions that attempt to erase auditable payment data
        return back()->with('error', 'Financial audit logs cannot be deleted. If required, change status to cancelled or refunded instead.');
    }
}