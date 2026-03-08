<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display all payments
     */
    public function index()
    {
        $payments = Payment::with('order.user')
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Not used (payments created automatically)
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Not used
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Show single payment
     */
    public function show(string $id)
    {
        $payment = Payment::with('order.user')->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Edit payment (admin updates status)
     */
    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);

        return view('admin.payments.edit', compact('payment'));
    }

    /**
     * Update payment status
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

        return redirect()
            ->route('admin.payments.show', $payment->id)
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Delete payment (optional)
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}