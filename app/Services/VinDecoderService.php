<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VinDecoderService
{
    /**
     * The API key for the service.
     */
    protected string $apiKey;

    /**
     * The base URL for the API.
     */
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.vehicledatabases.key');
        $this->baseUrl = config('services.vehicledatabases.base_url');
    }

    /**
     * Decode a 17-character VIN.
     */
    public function decode(string $vin): ?array
    {
        // Simple regex validation to save API credits
        if (!preg_php_match('/^[A-HJ-NPR-Z0-9]{17}$/i', $vin)) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->get($this->baseUrl . strtoupper($vin));

            if ($response->successful()) {
                return $response->json()['data'] ?? null;
            }

            // Log errors for debugging without crashing the app
            Log::error("VIN Decoder API Error: " . $response->status(), [
                'vin' => $vin,
                'body' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::critical("VIN Decoder Connection Failed: " . $e->getMessage());
        }

        return null;
    }
}