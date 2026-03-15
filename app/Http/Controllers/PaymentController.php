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

class PaymentController extends Controller
{
    protected $flwService;

    public function __construct(FlutterwaveService $flwService)
    {
        $this->flwService = $flwService;
    }


    public function process(Order $order)
{
    // Safety check: Ensure the person trying to pay is the owner of the order
    // if ($order->user_id !== auth()->id()) {
    //     abort(403, 'Unauthorized action.');
    // }

    // Return a view that will auto-submit a POST form to our initialize route
    return view('payment.process', [
        'order' => $order,
        'amount' => $order->total_amount
    ]);
}

    /**
     * Start the payment process and log the attempt.
     * Triggered by the POST request after the Livewire order is saved.
     */
    public function initialize(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'order_id' => 'required|exists:orders,id'
        ]);

        // Unique reference including Order ID for parsing later
        $reference = 'ASL-' . $request->order_id . '-' . time(); 

        PaymentLog::create([
            'user_id' => auth()->id(),
            'tx_ref' => $reference,
            'amount' => $request->amount,
            'currency' => 'RWF',
            'status' => 'pending'
        ]);

        $data = [
            'tx_ref' => $reference,
            'amount' => $request->amount,
            'currency' => 'RWF',
            'payment_options' => 'card,mobilemoneyrwanda',
            'redirect_url' => route('payment.callback'),
            'customer' => [
                'email' => auth()->user()->email,
                'name' => auth()->user()->name,
                'phonenumber' => auth()->user()->phone ?? '',
            ],
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

        // Log initialization failure
        PaymentLog::where('tx_ref', $reference)->update([
            'status' => 'failed',
            'error_message' => $payment['message'] ?? 'Gateway initialization failed',
            'raw_response' => json_encode($payment)
        ]);

        return redirect()->route('checkout')->with('error', 'Payment gateway unavailable. Please try again.');
    }

    /**
     * Handle the return redirect from Flutterwave
     */
    public function callback(Request $request)
    {
        $transactionId = $request->transaction_id;
        
        if (!$transactionId) {
            return redirect()->to('/')->with('error', 'Transaction was cancelled by user.');
        }

        $verification = $this->flwService->verifyTransaction($transactionId);

        if ($verification['status'] === 'success' && $verification['data']['status'] === 'successful') {
            $txRef = $verification['data']['tx_ref'];
            
            // 1. Update Payment Log to Successful
            PaymentLog::where('tx_ref', $txRef)->update([
                'status' => 'successful',
                'transaction_id' => $transactionId,
                'raw_response' => json_encode($verification)
            ]);

            // 2. Fulfillment Logic
            $this->finalizeOrder($txRef);

            return view('payment.success', ['data' => $verification['data']]);
        }

        // Handle Failure Logic
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

    /**
     * Finalize the Order after successful payment
     */
    private function finalizeOrder($txRef)
    {
        $parts = explode('-', $txRef);
        $orderId = $parts[1] ?? $txRef;

        $order = Order::where('id', $orderId)
                     ->with(['orderItems.part', 'user'])
                     ->first();

        if ($order && $order->status !== 'completed') {
            DB::transaction(function () use ($order, $txRef) {
                // 1. Update Order Status
                $order->update(['status' => 'completed']);

                // 2. Create/Update Payment record
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

                // 3. Inventory: Reduce stock for each part
                foreach ($order->orderItems as $item) {
                    if ($item->part) {
                        $item->part->decrement('stock_quantity', $item->quantity);
                    }
                }

                // 4. Set up Shipping
                Shipping::updateOrCreate(
                    ['order_id' => $order->id],
                    ['status' => 'pending']
                );
            });

            // 5. Send Invoice Email
            try {
                Mail::to($order->user->email)->send(new OrderPaidInvoice($order));
            } catch (\Exception $e) {
                // Log email failure but don't stop the process
                report($e);
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