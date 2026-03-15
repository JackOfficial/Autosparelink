<?php

namespace App\Http\Controllers;

use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PaymentLog;
use App\Models\Order; // Added Order model
use App\Models\Part; // Added Part/Part model
use App\Mail\OrderPaidInvoice;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    protected $flwService;

    public function __construct(FlutterwaveService $flwService)
    {
        $this->flwService = $flwService;
    }

    /**
     * Start the payment process and log the attempt
     */
  public function initialize(Request $request)
{
    // 1. Ensure order_id and amount are present
    $request->validate([
        'amount' => 'required|numeric',
        'order_id' => 'required|exists:orders,id'
    ]);

    // 2. Create a reference that includes the Order ID
    // We append a timestamp to make it unique in case they retry a failed payment
    $reference = 'ASL-' . $request->order_id . '-' . time(); 

    // 3. Log the intent to pay
    PaymentLog::create([
        'user_id' => auth()->id(),
        'tx_ref' => $reference,
        'amount' => $request->amount,
        'currency' => 'RWF', // Added currency for your log model
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
            'phonenumber' => auth()->user()->phone ?? '', // Good for MoMo
        ],
        'customizations' => [
            'title' => 'AutoSpareLink Payment',
            'description' => 'Payment for Order #' . $request->order_id,
            'logo' => asset('images/logo.png'),
        ],
    ];

    $payment = $this->flwService->initializePayment($data);

    if (isset($payment['status']) && $payment['status'] === 'success') {
        return redirect($payment['data']['link']);
    }

    // Handle initialization failure
    PaymentLog::where('tx_ref', $reference)->update([
        'status' => 'failed',
        'error_message' => $payment['message'] ?? 'Initialization failed',
        'raw_response' => json_encode($payment) // Keep the raw error for debugging
    ]);

    return back()->with('error', 'Payment gateway unavailable. Please try again.');
}

    /**
     * Handle the return redirect from Flutterwave
     */
    public function callback(Request $request)
    {
        $transactionId = $request->transaction_id;
        
        if (!$transactionId) {
           return redirect()->to('/')->with('error', 'Transaction was cancelled.');
        }

        $verification = $this->flwService->verifyTransaction($transactionId);

        if ($verification['status'] === 'success' && $verification['data']['status'] === 'successful') {
           $txRef = $verification['data']['tx_ref'] ?? $request->tx_ref;
            
            // 1. Update Payment Log
            $log = PaymentLog::where('tx_ref', $txRef)->first();
if ($log) {
    $log->update([
        'status' => 'failed',
        'transaction_id' => $transactionId,
        'error_message' => $verification['message'] ?? 'Payment failed',
        'raw_response' => json_encode($verification)
    ]);
}

            // 2. AutoSpareLink Fulfillment Logic
            $this->finalizeOrder($txRef);

            return view('payment.success', ['data' => $verification['data']]);
        }

        // Handle Failure
        $log = PaymentLog::where('tx_ref', $request->tx_ref)->first();
        if ($log) {
            $log->update([
                'status' => 'failed',
                'transaction_id' => $transactionId,
                'error_message' => $verification['message'] ?? 'Payment failed',
                'raw_response' => json_encode($verification)
            ]);
        }

        return view('payment.failed');
    }

    /**
 * Finalize the AutoSpareLink Order after successful payment
 */
private function finalizeOrder($txRef)
{
    // Extract the Order ID from 'ASL-123-170945600'
    $parts = explode('-', $txRef);
    $orderId = (isset($parts[1])) ? $parts[1] : $txRef;

    $order = Order::where('id', $orderId)
                  ->with('orderItems.part')
                  ->first();

    // Safety Check: Only finalize if the order exists and hasn't been completed yet
    if ($order && $order->status !== 'completed') {
        
        // 1. Update Order Status
        $order->update(['status' => 'completed']);

        // 2. Link the Payment record (using your specific Payment model)
        \App\Models\Payment::updateOrCreate(
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
                // Ensure stock doesn't go below zero (optional but recommended)
                $item->part->decrement('stock_quantity', $item->quantity);
            }
        }

        // 4. Update or Create Shipping record
        // Since your Checkout component creates a placeholder, use updateOrCreate
        \App\Models\Shipping::updateOrCreate(
            ['order_id' => $order->id],
            ['status' => 'pending']
        );

         // Inside finalizeOrder after setting order to completed
        Mail::to($order->user->email)->send(new OrderPaidInvoice($order));
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

        return $pdf->download('AutoSpareLink-Receipt-' . $data['tx_ref'] . '.pdf');
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