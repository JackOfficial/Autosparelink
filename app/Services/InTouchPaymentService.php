<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class InTouchPaymentService
{
    protected string $baseUrl;
    protected string $username;
    protected string $accountNo;
    protected string $partnerPassword;

    public function __construct()
    {
        $this->baseUrl = config('services.intouch.base_url', 'https://www.intouchpay.co.rw/api');
        $this->username = config('services.intouch.username');
        $this->accountNo = config('services.intouch.account_no');
        $this->partnerPassword = config('services.intouch.partner_password');
    }

    /**
     * RequestPayment: Receiving payment from a customer
     */
   public function requestPayment(string $phone, float $amount, string $requestId)
{
    $timestamp = Carbon::now('UTC')->format('YmdHis'); 
    $password = $this->generatePassword($timestamp);
    
    // Force HTTPS for the callback to prevent POST data loss via redirects
    // $callbackUrl = 'https://autospare-test.free.beeceptor.com';
    
    $callbackUrl = secure_url('api/payments/intouch/callback');

    $data = [
        'username'             => $this->username,
        'timestamp'            => $timestamp,
        'amount'               => $amount,
        'mobilephone'          => $this->formatNumber($phone), 
        'mobilephoneno'        => $this->formatNumber($phone), 
        'requesttransactionid' => $requestId,
        'accountno'            => $this->accountNo,
        'password'             => $password,
        'callbackurl'          => $callbackUrl,
    ];

    Log::info("Sending Callback URL to InTouch: " . $callbackUrl);

    $url = rtrim($this->baseUrl, '/') . '/requestpayment/';

    Log::info('InTouch Payment Request Initiated:', [
        'url' => $url,
        'callback' => $callbackUrl,
        'request_id' => $requestId
    ]);

    //$response = Http::asForm()->timeout(60)->connectTimeout(30)->post($url, $data);

    // Updated to bind to your whitelisted IP address interface
$response = Http::asForm()
    ->withOptions([
        'curl' => [
            CURLOPT_INTERFACE => '198.54.114.176',
        ],
    ])
    ->timeout(60)
    ->connectTimeout(30)
    ->post($url, $data);

    return $response->json();
}

    /**
     * RequestDeposit: Sending payment to a vendor
     */
   public function requestDeposit(string $phone, float $amount, string $requestId, string $reason = "Vendor Payout")
{
    $timestamp = Carbon::now('UTC')->format('YmdHis'); 
    $formattedPhone = $this->formatNumber($phone);
    
    // Logic to determine SID (Service ID)
    // 1 for MTN (078/079), 2 for Airtel (072/073) - Confirm these IDs with InTouch
    $sid = 1; 
    if (Str::startsWith($formattedPhone, '25072') || Str::startsWith($formattedPhone, '25073')) {
        $sid = 2; 
    }

    $data = [
        'username'             => $this->username,
        'timestamp'            => $timestamp,
        'amount'               => $amount,
        'mobilephone'          => $formattedPhone,
        'mobilephoneno'        => $formattedPhone,
        'requesttransactionid' => $requestId,
        'accountno'            => $this->accountNo,
        'password'             => $this->generatePassword($timestamp),
        'withdrawcharge'       => 1, // Usually 1 means the vendor pays the fee
        'reason'               => $reason,
        'sid'                  => $sid, 
    ];

    $url = rtrim($this->baseUrl, '/') . '/requestdeposit/';
    
    // Use the same robust timeout settings as requestPayment
    $response = Http::asForm()->timeout(60)->connectTimeout(30)->post($url, $data);

    return $response->json();
}

/**
 * Get the current account balance (Float)
 */
public function getBalance()
{
    $timestamp = Carbon::now('UTC')->format('YmdHis');
    
    $data = [
        'username'  => $this->username,
        'timestamp' => $timestamp,
        'accountno' => $this->accountNo,
        'password'  => $this->generatePassword($timestamp),
    ];

    $url = rtrim($this->baseUrl, '/') . '/getbalance/';

    try {
        $response = Http::asForm()->timeout(30)->post($url, $data);
        return $response->json();
    } catch (\Exception $e) {
        Log::error('InTouch Balance Check Error: ' . $e->getMessage());
        return ['success' => false, 'message' => 'Could not retrieve balance'];
    }
}

    /**
     * Get Transaction Status
     */
    public function getTransactionStatus(string $requestId, string $gatewayTransactionId)
    {
        // Note: Section 4.5 suggests yyyymmddss for this specific endpoint
        $timestamp = Carbon::now('UTC')->format('YmdHis');

        $data = [
            'username'             => $this->username,
            'timestamp'            => $timestamp,
            'password'             => $this->generatePassword($timestamp),
            'requesttransactionid' => $requestId,
            'transactionid'        => $gatewayTransactionId,
        ];

        $url = rtrim($this->baseUrl, '/') . '/gettransactionstatus/';
        $response = Http::asForm()->post($url, $data);

        return $response->json();
    }

    /**
     * Security: Generate SHA256 Hexdigest
     */
    private function generatePassword(string $timestamp): string
    {
        // Formula: Username+accountno+partnerpassword+timestamp
        $rawString = $this->username . $this->accountNo . $this->partnerPassword . $timestamp;
        
        return hash('sha256', $rawString); 
    }

    private function formatNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Ensure 250 prefix
        if (Str::startsWith($phone, '0')) {
            return '250' . substr($phone, 1);
        }
        
        return $phone;
    }
}