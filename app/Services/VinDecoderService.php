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

            if (!$response->successful()) {
        // ADD THIS TEMPORARILY:
        // dd("API Failed", $url, $response->status(), $response->body());
            }

            if ($response->successful()) {
                // Returns the whole JSON so Controller can verify ['status'] === 'success'
                return $response->json();
            }

            Log::error("VehicleDatabases API Error", [
                'endpoint' => $path,
                'status'   => $response->status(),
                'body'     => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::critical("VIN Service Connection Failed for {$path}: " . $e->getMessage());
        }

        return null;
    }
}