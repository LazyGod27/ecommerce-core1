<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function detect(Request $request)
    {
        $ip = $request->ip();

        try {
            // Free IP-based geolocation (ip-api.com)
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}");

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === 'success') {
                    return response()->json([
                        'ip'         => $ip,
                        'city'       => $data['city'] ?? null,
                        'region'     => $data['regionName'] ?? null,
                        'country'    => $data['country'] ?? null,
                        'postalCode' => $data['zip'] ?? null,
                        'lat'        => $data['lat'] ?? null,
                        'lon'        => $data['lon'] ?? null,
                        'timezone'   => $data['timezone'] ?? null,
                        'isp'        => $data['isp'] ?? null,
                    ]);
                }
            }

            return response()->json(['error' => 'Location detection failed'], 400);

        } catch (\Exception $e) {
            Log::error("IP Geolocation failed: " . $e->getMessage());
            return response()->json(['error' => 'Location service unavailable'], 500);
        }
    }
}
