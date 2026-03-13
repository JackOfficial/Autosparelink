<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VinDecoderService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.vehicle_api.key');
        // Final fallback to ensure the URL is never empty
        $this->baseUrl = rtrim(config('services.vehicle_api.base_url', 'https://api.vehicledatabases.com'), '/');
    }

    /**
     * PRIMARY: Advanced V2
     * Note: We ensure the path matches the 'advanced-vin-decode' requirement
     */
    public function decodeAdvanced(string $vin): ?array
    {
        // Some tiers use 'advanced-vin-decode/v2/decode' - we will try the standard first
        return $this->executeRequest("advanced-vin-decode/v2/{$vin}");
    }

    /**
     * FALLBACK: Europe V2
     */
    public function decodeEurope(string $vin): ?array
    {
        return $this->executeRequest("europe-vin-decode/v2/{$vin}");
    }

    /**
     * Shared request logic
     */
private function executeRequest(string $path): ?array
{
    try {
        $url = "{$this->baseUrl}/{$path}";

        $response = Http::withHeaders([
            'x-authkey' => $this->apiKey,
            'Accept'    => 'application/json',
        ])->timeout(15)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            
            // Handle cases where the API returns 200 OK but the body says "error"
            if (isset($data['status']) && $data['status'] === 'error') {
                Log::info("VIN API Info: {$path} - Record not found.");
                return null;
            }

            return $data;
        }

        // Log actual failures (400, 500, etc.) for your review later
        Log::warning("VIN API External Failure", [
            'path'   => $path,
            'status' => $response->status(),
            'body'   => $response->body()
        ]);

        return null;

    } catch (\Exception $e) {
        Log::critical("VIN Service Connection Failed: " . $e->getMessage());
        return null;
    }
}
}