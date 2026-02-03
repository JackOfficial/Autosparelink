<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VinController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'vin' => ['required', 'string', 'size:17'], // VINs are strictly 17 chars
        ]);

        $vin = strtoupper(trim($request->vin));
        $apiKey = config('services.vehicle_api.key', env('VEHICLE_API_KEY'));

        // 1. Try Advanced VIN Decode First
        $response = Http::withHeaders(['x-authkey' => $apiKey])
            ->get("https://api.vehicledatabases.com/advanced-vin-decode/v2/$vin");

        // 2. If it fails or finds nothing, try Europe Decode
        if ($response->failed() || empty($response->json()['data'])) {
            $response = Http::withHeaders(['x-authkey' => $apiKey])
                ->get("https://api.vehicledatabases.com/europe-vin-decode/v2/$vin");
        }

        // 3. Check if we finally got data
        if ($response->successful() && !empty($response->json()['data'])) {
            $vehicle = $response->json()['data'];
            
            // Logic to search your local database
            // $parts = Part::where('model', $vehicle['model'])->get();
            dd($vehicle);
            //return view('vin.results', compact('vehicle'));
        }
        dd("Vehicle records not found in Global or European databases.");
        //return back()->withErrors(['vin' => 'Vehicle records not found in Global or European databases.']);
    }
}
