<?php

namespace App\Http\Controllers;

use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PaymentLog;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipping;
use App\Mail\OrderPaidInvoice;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $flwService;

    public function __construct(FlutterwaveService $flwService)
    {
        $this->flwService = $flwService;
    }

    public function process(Order $order)
    {
        // For security, if the order belongs to a user, ensure only they can pay
        if ($order->user_id && $order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('payment.process', [
            'order' => $order,
            'amount' => $order->total_amount
        ]);
    }

    public function initialize(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::findOrFail($request->order_id);
        $reference = 'ASL-' . $request->order_id . '-' . time(); 

        // Create log (user_id will be null for guests)
        PaymentLog::create([
            'user_id' => auth()->id(),
            'tx_ref' => $reference,
            'amount' => $request->amount,
            'currency' => 'RWF',
            'status' => 'pending'
        ]);

        // Determine customer details: Use Auth if available, otherwise use Guest data from Order
        $customerData = [
            'email' => auth()->check() ? auth()->user()->email : $order->guest_email,
            'name' => auth()->check() ? auth()->user()->name : $order->guest_name,
            'phonenumber' => auth()->check() ? (auth()->user()->phone ?? '') : $order->guest_phone,
        ];

        $data = [
            'tx_ref' => $reference,
            'amount' => $request->amount,
            'currency' => 'RWF',
            'payment_options' => 'card,mobilemoneyrwanda',
            'redirect_url' => route('payment.callback'),
            'customer' => $customerData,
            'customizations' => [
                'title' => 'Happy Family Rwanda',
                'description' => 'Payment for Order #' . $request->order_id,
                'logo' => asset('images/logo.png'),
            ],
        ];

        $payment = $this->flwService->initializePayment($data);

        if (isset($payment['status']) && $payment['status'] === 'success') {
            return redirect($payment['data']['link']);
        }

        PaymentLog::where('tx_ref', $reference)->update([
            'status' => 'failed',
            'error_message' => $payment['message'] ?? 'Gateway initialization failed',
            'raw_response' => json_encode($payment)
        ]);

        return redirect()->to('checkout')->with('error', 'Payment gateway unavailable.');
    }

    public function callback(Request $request)
    {
        $transactionId = $request->transaction_id;
        
        if (!$transactionId) {
            return redirect()->to('/')->with('error', 'Transaction was cancelled.');
        }

        $verification = $this->flwService->verifyTransaction($transactionId);

        if ($verification['status'] === 'success' && $verification['data']['status'] === 'successful') {
            $txRef = $verification['data']['tx_ref'];
            
            PaymentLog::where('tx_ref', $txRef)->update([
                'status' => 'successful',
                'transaction_id' => $transactionId,
                'raw_response' => json_encode($verification)
            ]);

            $this->finalizeOrder($txRef);

            return view('payment.success', ['data' => $verification['data']]);
        }

        $txRef = $request->tx_ref ?? ($verification['data']['tx_ref'] ?? null);
        if ($txRef) {
            PaymentLog::where('tx_ref', $txRef)->update([
                'status' => 'failed',
                'transaction_id' => $transactionId,
                'error_message' => $verification['message'] ?? 'Payment verification failed',
                'raw_response' => json_encode($verification)
            ]);
        }

        return view('payment.failed');
    }

    private function finalizeOrder($txRef)
    {
        $parts = explode('-', $txRef);
        $orderId = $parts[1] ?? $txRef;

        $order = Order::where('id', $orderId)
                     ->with(['orderItems.part', 'user'])
                     ->first();

        if ($order && $order->status !== 'completed') {
            DB::transaction(function () use ($order, $txRef) {
                $order->update(['status' => 'processing']);

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'amount' => $order->total_amount,
                        'method' => 'flutterwave',
                        'transaction_reference' => $txRef,
                        'status' => 'successful',
                        'paid_at' => now()
                    ]
                );

                foreach ($order->orderItems as $item) {
                    if ($item->part) {
                        $item->part->decrement('stock_quantity', $item->quantity);
                    }
                }

                Shipping::updateOrCreate(
                    ['order_id' => $order->id],
                    ['status' => 'pending']
                );
            });

            // Send Invoice Email
            try {
                // Use the guest_email if the user relation is null
                $recipientEmail = $order->user ? $order->user->email : $order->guest_email;
                
                if ($recipientEmail) {
                    Mail::to($recipientEmail)->send(new OrderPaidInvoice($order));
                }
            } catch (\Exception $e) {
                Log::error('Invoice Email Failed: ' . $e->getMessage());
            }
        }
    }

    public function downloadReceipt($transactionId)
    {
        $verification = $this->flwService->verifyTransaction($transactionId);

        if ($verification['status'] !== 'success') {
            return back()->with('error', 'Could not generate receipt.');
        }

        $data = $verification['data'];
        $pdf = Pdf::loadView('payment.receipt', compact('data'));

        return $pdf->download('HappyFamily-Receipt-' . $data['tx_ref'] . '.pdf');
    }

    public function webhook(Request $request)
    {
        $signature = $request->header('verif-hash');
        if (!$signature || ($signature !== env('FLW_SECRET_HASH'))) {
            abort(401);
        }

        $payload = $request->all();

        if ($payload['status'] === 'successful') {
            $log = PaymentLog::where('tx_ref', $payload['tx_ref'])->first();
            
            if ($log && $log->status !== 'successful') {
                $log->update([
                    'status' => 'successful',
                    'transaction_id' => $payload['id'],
                    'raw_response' => json_encode($payload)
                ]);
                
                $this->finalizeOrder($payload['tx_ref']);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}