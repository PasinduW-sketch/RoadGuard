<?php

namespace App\Services;

class MapService
{
    /**
     * Find nearby providers using Haversine formula
     */
    public function findNearbyProviders($latitude, $longitude, $radiusKm = 10)
    {
        $providers = \App\Models\Provider::where('is_active', true)
            ->where('is_verified', true)
            ->get();

        $nearbyProviders = [];

        foreach ($providers as $provider) {
            $distance = $this->calculateDistance(
                $latitude,
                $longitude,
                $provider->latitude,
                $provider->longitude
            );

            if ($distance <= $radiusKm) {
                $provider->distance = $distance;
                $nearbyProviders[] = $provider;
            }
        }

        // Sort by distance
        usort($nearbyProviders, function ($a, $b) {
            return $a->distance <=> $b->distance;
        });

        return $nearbyProviders;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    /**
     * Get route between two points
     */
    public function getRoute($originLat, $originLng, $destLat, $destLng)
    {
        // TODO: Use Google Maps Directions API
        return [];
    }

    /**
     * Get estimated arrival time
     */
    public function getEstimatedArrivalTime($originLat, $originLng, $destLat, $destLng)
    {
        // TODO: Use Google Maps Distance Matrix API
        return null;
    }
}
