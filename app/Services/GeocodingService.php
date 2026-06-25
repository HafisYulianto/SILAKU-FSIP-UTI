<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GeocodingService
{
    /**
     * Geocode a location string to [lat, lng] using Nominatim (OpenStreetMap).
     * Returns [lat, lng] or null if not found.
     */
    public function geocode(string $location): ?array
    {
        if (empty(trim($location))) {
            return null;
        }

        // Cache results for 7 days to avoid repeated API calls for same location
        $cacheKey = 'geocode_' . md5(strtolower(trim($location)));

        return Cache::remember($cacheKey, 60 * 60 * 24 * 7, function () use ($location) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'SILAKU-FSIP-UTI/1.0 (academic research app)',
                    'Accept-Language' => 'id,en',
                ])->timeout(10)->get('https://nominatim.openstreetmap.org/search', [
                    'q'              => $location,
                    'format'         => 'json',
                    'limit'          => 1,
                    'addressdetails' => 0,
                ]);

                if ($response->successful() && !empty($response->json())) {
                    $result = $response->json()[0];
                    return [
                        'lat' => (float) $result['lat'],
                        'lng' => (float) $result['lon'],
                    ];
                }

                return null;
            } catch (\Exception $e) {
                Log::warning("GeocodingService: Failed to geocode \"{$location}\": " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Geocode and update an Alumni model's lat/lng columns.
     */
    public function geocodeAlumni(\App\Models\Alumni $alumni): bool
    {
        if (empty($alumni->lokasi)) {
            return false;
        }

        $coords = $this->geocode($alumni->lokasi);

        if ($coords) {
            $alumni->update([
                'lat' => $coords['lat'],
                'lng' => $coords['lng'],
            ]);
            return true;
        }

        return false;
    }
}
