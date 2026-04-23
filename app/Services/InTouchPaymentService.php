<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InTouchPaymentService
{
    protected string $baseUrl;
    protected string $username;
    protected string $accountNo;
    protected string $partnerPassword;

    public function __construct()
    {
        // Ensure these are set in your config/services.php
        $this->baseUrl = config('services.intouch.base_url', 'https://www.intouchpay.co.rw/api');
        $this->username = config('services.intouch.username');
        $this->accountNo = config('services.intouch.account_no');
        $this->partnerPassword = config('services.intouch.partner_password');
    }

    /**
     * RequestPayment: Receiving payment from a customer [cite: 9, 19]
     */
    public function requestPayment(string $phone, float $amount, string $requestId)
    {
        // Timestamp must be UTC formatted as yyyymmddhhmmss [cite: 71]
        $timestamp = Carbon::now('UTC')->format('Ymds');
        $password = $this->generatePassword($timestamp);
        
        $data = [
            'username'             => $this->username, // [cite: 71]
            'timestamp'            => $timestamp, // [cite: 71]
            'amount'               => $amount, // [cite: 71]
            'mobilephoneno'        => $this->formatNumber($phone), // [cite: 71]
            'requesttransactionid' => $requestId, // [cite: 71]
            'accountno'            => $this->accountNo, // [cite: 71]
            'password'             => $password, // [cite: 66, 73]
            'callbackurl'          => route('api.payments.intouch.callback'), // [cite: 62, 73]
        ];

        // Parameters are submitted as http-form post [cite: 14, 63]
        $response = Http::asForm()->post("{$this->baseUrl}/requestpayment/", $data);

        return $response->json(); // Response format is json [cite: 16, 75]
    }

    /**
     * RequestDeposit: Sending payment to a vendor [cite: 9, 114]
     */
    public function requestDeposit(string $phone, float $amount, string $requestId, string $reason = "Vendor Payout")
    {
        $timestamp = Carbon::now('UTC')->format('YmdHis'); // [cite: 157]

        $data = [
            'username'             => $this->username, // [cite: 157]
            'timestamp'            => $timestamp, // [cite: 157]
            'amount'               => $amount, // [cite: 157]
            'mobilephoneno'        => $this->formatNumber($phone), // [cite: 157]
            'requesttransactionid' => $requestId, // [cite: 157]
            'accountno'            => $this->accountNo, // [cite: 157]
            'password'             => $this->generatePassword($timestamp), // [cite: 151, 157]
            'withdrawcharge'       => 1, // Set to 1 to include charges if required [cite: 157]
            'reason'               => $reason, // [cite: 157]
            'sid'                  => 1, // Set to 1 for Bulk Payments [cite: 157]
        ];

        // Parameters are submitted as http-form post [cite: 14, 148]
        $response = Http::asForm()->post("{$this->baseUrl}/requestdeposit/", $data);

        return $response->json(); // [cite: 16, 159]
    }

    /**
     * Security: Generate SHA256 Hexdigest [cite: 66, 151]
     */
    private function generatePassword(string $timestamp): string
    {
        // Formula: Username+accountno+partnerpassword+timestamp [cite: 66, 151, 191]
        $rawString = $this->username . $this->accountNo . $this->partnerPassword . $timestamp;
        
        // Encrypt using SHA256 and return hexdigest [cite: 66, 67, 152, 153]
        return hash('sha256', $rawString); 
    }

    /**
     * Get Transaction Status: Query the status of a transaction [cite: 181]
     */
    public function getTransactionStatus(string $requestId, string $gatewayTransactionId)
    {
        $timestamp = Carbon::now('UTC')->format('YmdHis');

        $data = [
            'username'             => $this->username, // [cite: 192]
            'timestamp'            => $timestamp, // [cite: 192]
            'password'             => $this->generatePassword($timestamp), // [cite: 191, 192]
            'requesttransactionid' => $requestId, // [cite: 192]
            'transactionid'        => $gatewayTransactionId, // [cite: 192]
        ];

        // Uses POST to query status [cite: 189]
        $response = Http::asForm()->post("{$this->baseUrl}/gettransactionstatus/", $data);

        return $response->json(); // [cite: 193]
    }

    private function formatNumber(string $phone): string
    {
        // Documentation examples show the 250 prefix (e.g., 250785971082) [cite: 60, 145]
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (Str::startsWith($phone, '0')) {
            return '250' . substr($phone, 1);
        }
        
        return $phone;
    }
}