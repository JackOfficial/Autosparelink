<?php

namespace App\Http\Controllers;

use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PaymentLog;

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
        $request->validate(['amount' => 'required|numeric']);
        
        $reference = 'ASL-' . Str::upper(Str::random(10)); 

        // 1. Create the log entry as 'pending'
        PaymentLog::create([
            'user_id' => auth()->id(),
            'tx_ref' => $reference,
            'amount' => $request->amount,
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
            ],
            'customizations' => [
                'title' => 'Autosparelink Payment',
                'logo' => asset('images/logo.png'),
            ],
        ];

        $payment = $this->flwService->initializePayment($data);

        if (isset($payment['status']) && $payment['status'] === 'success') {
            return redirect($payment['data']['link']);
        }

        // 2. Log the initialization failure
        PaymentLog::where('tx_ref', $reference)->update([
            'status' => 'failed',
            'error_message' => $payment['message'] ?? 'Initialization failed'
        ]);

        return back()->with('error', 'Payment failed to start. Please try again.');
    }

    /**
     * Handle the return redirect from Flutterwave
     */
    public function callback(Request $request)
    {
        $transactionId = $request->transaction_id;
        
        if (!$transactionId) {
            return redirect()->route('home')->with('error', 'Transaction was cancelled.');
        }

        $verification = $this->flwService->verifyTransaction($transactionId);

        // 3. Update log based on verification
        $log = PaymentLog::where('tx_ref', $verification['data']['tx_ref'])->first();

        if ($verification['status'] === 'success' && $verification['data']['status'] === 'successful') {
            if ($log) {
                $log->update([
                    'status' => 'successful',
                    'transaction_id' => $transactionId,
                    'raw_response' => json_encode($verification)
                ]);
            }

            // SUCCESS: logic to update order status or SMM balance would go here
            return view('payment.success', ['data' => $verification['data']]);
        }

        // 4. Log the specific failure reason
        if ($log) {
            $log->update([
                'status' => 'failed',
                'transaction_id' => $transactionId,
                'error_message' => $verification['message'] ?? 'User cancelled or network error',
                'raw_response' => json_encode($verification)
            ]);
        }

        return view('payment.failed');
    }

    /**
     * Generate and download the PDF receipt
     */
    public function downloadReceipt($transactionId)
    {
        $verification = $this->flwService->verifyTransaction($transactionId);

        if ($verification['status'] !== 'success') {
            return back()->with('error', 'Could not generate receipt.');
        }

        $data = $verification['data'];
        
        $pdf = Pdf::loadView('payment.receipt', compact('data'));

        return $pdf->download('Receipt-' . $data['tx_ref'] . '.pdf');
    }

    /**
     * Handle the background Webhook from Flutterwave
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('verif-hash');
        if (!$signature || ($signature !== env('FLW_SECRET_HASH'))) {
            abort(401);
        }

        $payload = $request->all();

        if ($payload['status'] === 'successful') {
            // Background update logic (Ensure we don't double-process if callback already finished)
            $log = PaymentLog::where('tx_ref', $payload['tx_ref'])->first();
            
            if ($log && $log->status !== 'successful') {
                $log->update([
                    'status' => 'successful',
                    'transaction_id' => $payload['id'],
                    'raw_response' => json_encode($payload)
                ]);
                
                // Finalize order here
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}