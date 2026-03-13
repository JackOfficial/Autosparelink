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
        // Ensure this is: https://api.vehicledatabases.com
        $this->baseUrl = rtrim(config('services.vehicle_api.base_url'), '/');
    }

    /**
     * PRIMARY: Advanced V2 (Good for US/Global/General)
     */
    public function decodeAdvanced(string $vin): ?array
    {
        return $this->executeRequest("advanced-vin-decode/v2/{$vin}");
    }

    /**
     * FALLBACK: Europe V2 (Crucial for Rwanda - European/Korean/Dubai imports)
     */
    public function decodeEurope(string $vin): ?array
    {
        return $this->executeRequest("europe-vin-decode/v2/{$vin}");
    }

    /**
     * Shared request logic to keep code DRY
     */
    private function executeRequest(string $path): ?array
{
    try {
        $url = "{$this->baseUrl}/{$path}";

        $response = Http::withHeaders([
            'x-authkey' => $this->apiKey,
            'Accept'    => 'application/json',
        ])->timeout(12)->get($url);

        // DEBUG: If the Europe request (which has 'europe' in the path) fails, show why.
        if (!$response->successful() && str_contains($path, 'europe')) {
            dd("Europe API Failed", $url, $response->status(), $response->body());
        }

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    } catch (\Exception $e) {
        Log::critical("Connection Failed: " . $e->getMessage());
        return null;
    }
}
}