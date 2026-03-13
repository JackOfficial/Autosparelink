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

        // --- THE LOUD DEBUGGER ---
        // If we are testing 'advanced' and it's NOT successful, STOP and show why.
        if (str_contains($path, 'advanced') && !$response->successful()) {
            dd([
                'DEBUG_STEP' => 'Advanced API Failed',
                'URL' => $url,
                'HTTP_STATUS' => $response->status(),
                'SERVER_MESSAGE' => $response->body(),
                'HINT' => 'If status is 400, the VIN failed checksum. If 401, check your key.'
            ]);
        }

        if ($response->successful()) {
            $data = $response->json();
            
            // Check for internal API "error" status even on 200 OK
            if (isset($data['status']) && $data['status'] === 'error') {
                if (str_contains($path, 'advanced')) {
                    dd('Advanced API returned 200 OK but Internal Error:', $data);
                }
                return null;
            }

            return $data;
        }

        return null;

    } catch (\Exception $e) {
        dd("Critical Connection Error: " . $e->getMessage());
    }
}
}