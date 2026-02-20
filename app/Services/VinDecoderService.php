<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class VinDecoderService
{
    /**
     * Decode the VIN using the external API.
     */
    public function decode(string $vin): ?array
    {
        $apiKey = config('services.vehicle_api.key');
        
        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
        ])->get("https://api.vehicledatabases.com/vin-decode/{$vin}");

        return $response->successful() ? $response->json()['data'] : null;
    }
}