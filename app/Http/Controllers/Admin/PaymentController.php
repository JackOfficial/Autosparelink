<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display all payments with eager loaded order and user data
     */
    public function index()
    {
        $payments = Payment::with('order.user')
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show single payment details
     */
    public function show(string $id)
    {
        $payment = Payment::with(['order.user', 'order.orderItems.part'])->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Edit payment status
     */
    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        
        // Match the statuses allowed in your validation
        $statuses = ['pending', 'processing', 'successful', 'failed', 'refunded'];

        return view('admin.payments.edit', compact('payment', 'statuses'));
    }

    /**
     * Update payment status and sync with Order if necessary
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,successful,failed,refunded'
        ]);

        $payment = Payment::findOrFail($id);

        $payment->update([
            'status' => $request->status
        ]);

        // Logic Sync: If payment is successful, ensure the order moves out of 'pending'
        if ($request->status === 'successful' && $payment->order->status === 'pending') {
            $payment->order->update(['status' => 'processing']);
        }

        return redirect()
            ->route('admin.payments.show', $payment->id)
            ->with('success', "Payment #{$payment->id} marked as " . ucfirst($request->status));
    }

    /**
     * Delete payment
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Payment record deleted successfully.');
    }
}