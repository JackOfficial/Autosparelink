<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlutterwaveService
{
    protected $secretKey;
    protected $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key');
    }

    public function initializePayment(array $data)
    {
        $response = Http::withToken($this->secretKey)
            ->post($this->baseUrl . '/payments', $data);

        return $response->json();
    }

    public function verifyTransaction($transactionId)
    {
        $response = Http::withToken($this->secretKey)
            ->get($this->baseUrl . "/transactions/{$transactionId}/verify");

        return $response->json();
    }
}