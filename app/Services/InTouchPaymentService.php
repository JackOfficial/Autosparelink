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
    protected string $outgoingIp;

    public function __construct()
    {
        $this->baseUrl = config('services.intouch.base_url', 'https://www.intouchpay.co.rw/api');
        $this->username = config('services.intouch.username');
        $this->accountNo = config('services.intouch.account_no');
        $this->partnerPassword = config('services.intouch.partner_password');
        // Centralize whitelisted IP context
        $this->outgoingIp = '198.54.114.176'; 
    }

    /**
     * RequestPayment: Collect mobile money from a subscriber
     */
    public function requestPayment(string $phone, float $amount, string $requestId)
    {
        $timestamp = Carbon::now('UTC')->format('YmdHis'); 
        $password = $this->generatePassword($timestamp);
        $callbackUrl = secure_url('api/payments/intouch/callback');
        $formattedPhone = $this->formatNumber($phone);

        $data = [
            'username'             => $this->username,
            'timestamp'            => $timestamp,
            'amount'               => $amount,
            'mobilephone'          => $formattedPhone, 
            'mobilephoneno'        => $formattedPhone, 
            'requesttransactionid' => $requestId,
            'accountno'            => $this->accountNo,
            'password'             => $password,
            'callbackurl'          => $callbackUrl,
        ];

        $url = rtrim($this->baseUrl, '/') . '/requestpayment/';

        try {
            $response = Http::asForm()
                ->withOptions([
                    'curl' => [CURLOPT_INTERFACE => $this->outgoingIp],
                ])
                ->timeout(60)
                ->connectTimeout(30)
                ->post($url, $data);

            return $response->json() ?? ['success' => false, 'message' => 'Malformed or empty JSON response from gateway'];

        } catch (\Exception $e) {
            Log::error('InTouch requestPayment Critical Exception: ' . $e->getMessage(), ['request_id' => $requestId]);
            return ['success' => false, 'message' => 'Gateway connection failure: ' . $e->getMessage()];
        }
    }

    /**
     * RequestDeposit: Send mobile money payout to a vendor/user wallet
     */
    public function requestDeposit(string $phone, float $amount, string $requestId, string $reason = "Vendor Payout")
    {
        $timestamp = Carbon::now('UTC')->format('YmdHis'); 
        $formattedPhone = $this->formatNumber($phone);
        
        // Dynamically compute Rwandan carrier routing SIDs securely
        $sid = $this->resolveServiceId($formattedPhone);

        $data = [
            'username'             => $this->username,
            'timestamp'            => $timestamp,
            'amount'               => $amount,
            'mobilephone'          => $formattedPhone,
            'mobilephoneno'        => $formattedPhone,
            'requesttransactionid' => $requestId,
            'accountno'            => $this->accountNo,
            'password'             => $this->generatePassword($timestamp),
            'withdrawcharge'       => 1, 
            'reason'               => Str::limit($reason, 100, ''), // Clean bounds verification
            'sid'                  => $sid, 
        ];

        $url = rtrim($this->baseUrl, '/') . '/requestdeposit/';
        
        try {
            $response = Http::asForm()
                ->withOptions([
                    'curl' => [CURLOPT_INTERFACE => $this->outgoingIp],
                ])
                ->timeout(60)
                ->connectTimeout(30)
                ->post($url, $data);

            return $response->json() ?? ['success' => false, 'message' => 'Malformed response signature during deposit request'];

        } catch (\Exception $e) {
            Log::error('InTouch requestDeposit Critical Exception: ' . $e->getMessage(), ['request_id' => $requestId]);
            return ['success' => false, 'message' => 'Gateway payout communication exception: ' . $e->getMessage()];
        }
    }

    /**
     * Get the current account float balance
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
            $response = Http::asForm()
                ->withOptions([
                    'curl' => [CURLOPT_INTERFACE => $this->outgoingIp],
                ])
                ->timeout(45)
                ->connectTimeout(20)
                ->post($url, $data);

            return $response->json() ?? ['success' => false, 'message' => 'Invalid balance schema context'];
        } catch (\Exception $e) {
            Log::error('InTouch Balance Check Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Could not retrieve balance due to transport failure'];
        }
    }

    /**
     * Get Transaction Status (Outbound Verification)
     */
    public function getTransactionStatus(string $requestId, string $gatewayTransactionId)
    {
        $timestamp = Carbon::now('UTC')->format('YmdHis');

        $data = [
            'username'             => $this->username,
            'timestamp'            => $timestamp,
            'password'             => $this->generatePassword($timestamp),
            'requesttransactionid' => $requestId,
            'transactionid'        => $gatewayTransactionId,
        ];

        $url = rtrim($this->baseUrl, '/') . '/gettransactionstatus/';

        try {
            $response = Http::asForm()
                ->withOptions([
                    'curl' => [CURLOPT_INTERFACE => $this->outgoingIp],
                ])
                ->timeout(45)
                ->connectTimeout(20)
                ->post($url, $data);

            return $response->json() ?? ['success' => false, 'message' => 'Invalid status schema context'];
        } catch (\Exception $e) {
            Log::error('InTouch Status Query Exception: ' . $e->getMessage(), ['request_id' => $requestId]);
            return ['success' => false, 'message' => 'Status request failed: ' . $e->getMessage()];
        }
    }

    /**
     * Security: Generate SHA256 Hash Signature matching InTouch specification rules
     */
    private function generatePassword(string $timestamp): string
    {
        return hash('sha256', $this->username . $this->accountNo . $this->partnerPassword . $timestamp); 
    }

    /**
     * Resilient formatting for local telephone allocations
     */
    private function formatNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Handle local shorthand notation (e.g., 078...)
        if (Str::startsWith($phone, '0')) {
            return '250' . substr($phone, 1);
        }
        
        // Handle raw notation missing prefix entirely (e.g., 788...)
        if (strlen($phone) === 9 && (Str::startsWith($phone, '78') || Str::startsWith($phone, '79') || Str::startsWith($phone, '72') || Str::startsWith($phone, '73'))) {
            return '250' . $phone;
        }
        
        return $phone;
    }

    /**
     * Internal utility map matching network prefix pools to operational Service IDs
     */
    private function resolveServiceId(string $formattedPhone): int
    {
        // Strip 250 country code prefix out to analyze structural mobile subscriber roots safely
        $localBody = substr($formattedPhone, 3);

        // Airtel Rwanda Identification Prefixes
        if (Str::startsWith($localBody, ['72', '73'])) {
            return 2; 
        }

        // Default fallback to MTN Rwanda (SID 1) for standard '78' and split '79' allocations
        return 1;
    }
}