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
        // Fixed: Section 1.4 requires 14 digits (yyyymmddhhmmss)
        $timestamp = Carbon::now('UTC')->format('YmdHis'); 
        $password = $this->generatePassword($timestamp);
        
        $data = [
            'username'             => $this->username,
            'timestamp'            => $timestamp,
            'amount'               => $amount,
            // Fixed: Included both keys to satisfy both the Python example and the Table spec
            'mobilephone'          => $this->formatNumber($phone), 
            'mobilephoneno'        => $this->formatNumber($phone), 
            'requesttransactionid' => $requestId,
            'accountno'            => $this->accountNo,
            'password'             => $password,
            'callbackurl'          => route('api.payments.intouch.callback'),
        ];

        // Ensure trailing slash as per Section 1.1
        $url = rtrim($this->baseUrl, '/') . '/requestpayment/';

        // Submitted as http-form post
        $response = Http::asForm()->timeout(60)->connectTimeout(30)->post($url, $data);

        return $response->json();
    }

    /**
     * RequestDeposit: Sending payment to a vendor
     */
    public function requestDeposit(string $phone, float $amount, string $requestId, string $reason = "Vendor Payout")
    {
        $timestamp = Carbon::now('UTC')->format('YmdHis'); 

        $data = [
            'username'             => $this->username,
            'timestamp'            => $timestamp,
            'amount'               => $amount,
            'mobilephone'          => $this->formatNumber($phone),
            'mobilephoneno'        => $this->formatNumber($phone),
            'requesttransactionid' => $requestId,
            'accountno'            => $this->accountNo,
            'password'             => $this->generatePassword($timestamp),
            'withdrawcharge'       => 1, 
            'reason'               => $reason,
            'sid'                  => 1, 
        ];

         $url = rtrim($this->baseUrl, '/') . '/requestdeposit/';

        Log::info('InTouch Payment Request Sent:', [
    'request_id' => $requestId,
    'phone' => $phone,
    'amount' => $amount,
    'url' => $url
     ]);

       
        $response = Http::asForm()->post($url, $data);

        return $response->json();
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