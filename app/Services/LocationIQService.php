<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationIQService
{
    private string $baseUrl;
    private string $apiKey;
    private int $timeout;
    private array $defaultParams;
    private bool $logRequests;
    private bool $logResponses;

    public function __construct()
    {
        $this->baseUrl = config('locationiq.base_url');
        $apiKey = config('locationiq.api_key');

        if (empty($apiKey)) {
            throw new \Exception('LocationIQ API key is not configured. Please set LOCATIONIQ_API_KEY in your .env file.');
        }

        $this->apiKey = $apiKey;
        $this->timeout = config('locationiq.timeout');
        $this->defaultParams = config('locationiq.default_params');
        $this->logRequests = config('locationiq.log_requests');
        $this->logResponses = config('locationiq.log_responses');
    }

    /**
     * Forward geocoding - search for places by name/address
     *
     * @param string $query The address or place name to search for
     * @param array $options Additional search options
     * @return array
     * @throws \Exception
     */
    public function search(string $query, array $options = []): array
    {
        $params = array_merge($this->defaultParams, $options, [
            'key' => $this->apiKey,
            'q' => $query,
        ]);

        $url = $this->baseUrl . '/search.php';

        if ($this->logRequests) {
            Log::info('LocationIQ Search Request', [
                'url' => $url,
                'params' => $params,
            ]);
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withOptions(['verify' => false]) // Bypass SSL verification for Windows
                ->get($url, $params);

            if ($this->logResponses) {
                Log::info('LocationIQ Search Response', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            if (!$response->successful()) {
                throw new \Exception('LocationIQ API request failed: ' . $response->body());
            }

            $data = $response->json();

            if (empty($data)) {
                return [];
            }

            return $this->formatSearchResults($data);
        } catch (\Exception $e) {
            Log::error('LocationIQ Search Error', [
                'message' => $e->getMessage(),
                'query' => $query,
            ]);
            throw $e;
        }
    }

    /**
     * Get detailed information about a specific place
     *
     * @param string $placeId The place_id from search results
     * @return array|null
     * @throws \Exception
     */
    public function getPlaceDetails(string $placeId): ?array
    {
        $params = [
            'key' => $this->apiKey,
            'place_id' => $placeId,
            'format' => 'json',
            'addressdetails' => 1,
            'extratags' => 1,
            'namedetails' => 1,
        ];

        $url = $this->baseUrl . '/details.php';

        if ($this->logRequests) {
            Log::info('LocationIQ Details Request', [
                'url' => $url,
                'params' => $params,
            ]);
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withOptions(['verify' => false]) // Bypass SSL verification for Windows
                ->get($url, $params);

            if ($this->logResponses) {
                Log::info('LocationIQ Details Response', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

            if (!$response->successful()) {
                throw new \Exception('LocationIQ API request failed: ' . $response->body());
            }

            $data = $response->json();

            return $data ? $this->formatPlaceDetails($data) : null;
        } catch (\Exception $e) {
            Log::error('LocationIQ Details Error', [
                'message' => $e->getMessage(),
                'place_id' => $placeId,
            ]);
            throw $e;
        }
    }

    /**
     * Format search results for display
     */
    private function formatSearchResults(array $results): array
    {
        return array_map(function ($result) {
            return [
                'place_id' => $result['place_id'] ?? null,
                'display_name' => $result['display_name'] ?? '',
                'lat' => (float) ($result['lat'] ?? 0),
                'lon' => (float) ($result['lon'] ?? 0),
                'type' => $result['type'] ?? '',
                'class' => $result['class'] ?? '',
                'importance' => (float) ($result['importance'] ?? 0),
                'address' => $this->formatAddress($result['address'] ?? []),
                'bounding_box' => $result['boundingbox'] ?? [],
                'raw' => $result,
            ];
        }, $results);
    }

    /**
     * Format place details
     */
    private function formatPlaceDetails(array $data): array
    {
        return [
            'place_id' => $data['place_id'] ?? null,
            'display_name' => $data['display_name'] ?? '',
            'lat' => (float) ($data['lat'] ?? 0),
            'lon' => (float) ($data['lon'] ?? 0),
            'type' => $data['type'] ?? '',
            'class' => $data['class'] ?? '',
            'importance' => (float) ($data['importance'] ?? 0),
            'address' => $this->formatAddress($data['address'] ?? []),
            'bounding_box' => $data['boundingbox'] ?? [],
            'extratags' => $data['extratags'] ?? [],
            'namedetails' => $data['namedetails'] ?? [],
            'raw' => $data,
        ];
    }

    /**
     * Format address components
     */
    private function formatAddress(array $address): array
    {
        return [
            'house_number' => $address['house_number'] ?? '',
            'road' => $address['road'] ?? '',
            'suburb' => $address['suburb'] ?? $address['neighbourhood'] ?? '',
            'city' => $address['city'] ?? $address['town'] ?? $address['village'] ?? '',
            'county' => $address['county'] ?? '',
            'state' => $address['state'] ?? '',
            'postcode' => $address['postcode'] ?? '',
            'country' => $address['country'] ?? '',
            'country_code' => $address['country_code'] ?? '',
        ];
    }

    /**
     * Get account usage/quota information
     */
    public function getAccountInfo(): array
    {
        $params = [
            'key' => $this->apiKey,
            'format' => 'json',
        ];

        $url = $this->baseUrl . '/balance.php';

        try {
            $response = Http::timeout($this->timeout)
                ->withOptions(['verify' => false]) // Bypass SSL verification for Windows
                ->get($url, $params);

            if (!$response->successful()) {
                throw new \Exception('LocationIQ API request failed: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('LocationIQ Account Info Error', [
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
