<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AddressSQService
{
    private string $baseUrl;
    private string $username;
    private string $password;
    private ?string $bearerToken = null;
    private int $companyId;
    private int $serviceTypeId;

    public function __construct()
    {
        $this->baseUrl = config('address_sq.base_url');
        $this->username = config('address_sq.credentials.username');
        $this->password = config('address_sq.credentials.password');
        $this->companyId = config('address_sq.default_company_id');
        $this->serviceTypeId = config('address_sq.default_service_type_id');
    }

    /**
     * Login and retrieve bearer token
     */
    public function authenticate(): bool
    {
        try {
            $loginUrl = $this->baseUrl . config('address_sq.endpoints.login');
            $credentials = [
                'email' => $this->username,
                'password' => $this->password,
            ];

            $response = Http::timeout(config('address_sq.timeout', 30))
                ->post($loginUrl, $credentials);

            if ($response->successful()) {
                $data = $response->json();

                // The API returns token in result.token structure
                $this->bearerToken = $data['result']['token'] ?? $data['token'] ?? null;

                if ($this->bearerToken) {
                    if (config('address_sq.log_requests')) {
                        Log::info('Successfully authenticated with Address SQ API');
                    }
                    return true;
                } else {
                    Log::error('No token found in successful response', [
                        'response_data' => $data
                    ]);
                }
            }

            if (config('address_sq.log_responses')) {
                Log::error('Failed to authenticate with Address SQ API', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
            }

            return false;
        } catch (Exception $e) {
            Log::error('Exception during Address SQ API authentication', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Find address using the provided parameters
     */
    public function findAddress(array $params): array
    {
        if (!$this->bearerToken && !$this->authenticate()) {
            throw new Exception('Authentication failed');
        }

        try {
            $requestData = [
                'company_id' => $this->companyId,
                'street_number' => $params['street_number'] ?? null,
                'street_name' => $params['street_name'],
                'street_type' => $params['street_type'],
                'suburb' => $params['suburb'],
                'postcode' => $params['postcode'],
                'state' => $params['state'],
            ];

            $response = Http::timeout(config('address_sq.timeout', 30))
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post($this->baseUrl . config('address_sq.endpoints.find_address'), $requestData);

            if ($response->successful()) {
                $data = $response->json();
                if (config('address_sq.log_requests')) {
                    Log::info('Address search successful', ['request' => $requestData]);
                }
                return $data;
            }

            if (config('address_sq.log_responses')) {
                Log::error('Address search failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'request' => $requestData
                ]);
            }

            throw new Exception('Address search failed: ' . $response->body());
        } catch (Exception $e) {
            Log::error('Exception during address search', [
                'message' => $e->getMessage(),
                'params' => $params
            ]);
            throw $e;
        }
    }

    /**
     * Qualify service for a specific address identifier
     */
    public function qualifyService(string $qualificationIdentifier): array
    {
        if (!$this->bearerToken && !$this->authenticate()) {
            throw new Exception('Authentication failed');
        }

        try {
            $requestData = [
                'company_id' => $this->companyId,
                'qualification_identifier' => $qualificationIdentifier,
                'service_type_id' => $this->serviceTypeId,
            ];

            $response = Http::timeout(config('address_sq.timeout', 30))
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post($this->baseUrl . config('address_sq.endpoints.qualify'), $requestData);

            if ($response->successful()) {
                $data = $response->json();
                if (config('address_sq.log_requests')) {
                    Log::info('Service qualification successful', [
                        'qualification_identifier' => $qualificationIdentifier
                    ]);
                }
                return $data;
            }

            if (config('address_sq.log_responses')) {
                Log::error('Service qualification failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'request' => $requestData
                ]);
            }

            throw new Exception('Service qualification failed: ' . $response->body());
        } catch (Exception $e) {
            Log::error('Exception during service qualification', [
                'message' => $e->getMessage(),
                'qualification_identifier' => $qualificationIdentifier
            ]);
            throw $e;
        }
    }

    /**
     * Get available street types for dropdown
     */
    public static function getStreetTypes(): array
    {
        return [
            'Avenue' => 'Avenue',
            'Boulevard' => 'Boulevard',
            'Circuit' => 'Circuit',
            'Close' => 'Close',
            'Court' => 'Court',
            'Crescent' => 'Crescent',
            'Drive' => 'Drive',
            'Grove' => 'Grove',
            'Highway' => 'Highway',
            'Lane' => 'Lane',
            'Parade' => 'Parade',
            'Place' => 'Place',
            'Road' => 'Road',
            'Street' => 'Street',
            'Terrace' => 'Terrace',
            'Way' => 'Way',
        ];
    }

    /**
     * Get available Australian states
     */
    public static function getStates(): array
    {
        return [
            'NSW' => 'New South Wales',
            'VIC' => 'Victoria',
            'QLD' => 'Queensland',
            'WA' => 'Western Australia',
            'SA' => 'South Australia',
            'TAS' => 'Tasmania',
            'NT' => 'Northern Territory',
            'ACT' => 'Australian Capital Territory',
        ];
    }

    /**
     * Parse copper pair records from XML string
     */
    public function parseCopperPairRecords(array $copperPairRecords): array
    {
        $parsed = [];

        foreach ($copperPairRecords as $xmlString) {
            try {
                $xml = simplexml_load_string($xmlString);
                if ($xml) {
                    $parsed[] = [
                        'copper_pair_id' => (string) $xml->CopperPairID,
                        'copper_pair_status' => (string) $xml->CopperPairStatus,
                        'nbn_service_status' => (string) $xml->NBNServiceStatus,
                        'service_class' => (string) $xml->ServiceClass,
                        'tc2_speed' => (string) $xml->TC2Speed,
                        'upload_speed' => (string) $xml->UploadSpeed,
                        'download_speed' => (string) $xml->DownloadSpeed,
                        'network_co_exist' => (string) $xml->NetworkCoExist,
                        'extra_charge' => (string) $xml->ExtraCharge,
                    ];
                }
            } catch (Exception $e) {
                Log::warning('Failed to parse copper pair record', [
                    'xml' => $xmlString,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $parsed;
    }
}
