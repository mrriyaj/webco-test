<?php

namespace Tests\Unit;

use App\Services\LocationIQService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LocationIQServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('locationiq.base_url', 'https://eu1.locationiq.com/v1');
        Config::set('locationiq.api_key', 'test-api-key');
        Config::set('locationiq.timeout', 30);
        Config::set('locationiq.default_params', [
            'format' => 'json',
            'limit' => 10,
            'addressdetails' => 1,
            'extratags' => 1,
            'namedetails' => 1,
        ]);
        Config::set('locationiq.log_requests', false);
        Config::set('locationiq.log_responses', false);
    }

    public function test_constructor_throws_exception_when_api_key_missing()
    {
        Config::set('locationiq.api_key', null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('LocationIQ API key is not configured');

        new LocationIQService();
    }

    public function test_search_returns_formatted_results()
    {
        $mockResponse = [
            [
                'place_id' => '12345',
                'display_name' => 'Sydney Opera House, Sydney, Australia',
                'lat' => '-33.8567844',
                'lon' => '151.2152967',
                'type' => 'theatre',
                'class' => 'amenity',
                'importance' => 0.751,
                'address' => [
                    'house_number' => '1',
                    'road' => 'Macquarie Street',
                    'suburb' => 'Sydney',
                    'city' => 'Sydney',
                    'state' => 'New South Wales',
                    'postcode' => '2000',
                    'country' => 'Australia',
                    'country_code' => 'au',
                ],
                'boundingbox' => ['-33.8577844', '-33.8557844', '151.2142967', '151.2162967'],
            ]
        ];

        Http::fake([
            'https://eu1.locationiq.com/v1/search.php*' => Http::response($mockResponse, 200),
        ]);

        $service = new LocationIQService();
        $results = $service->search('Sydney Opera House');

        $this->assertCount(1, $results);
        $this->assertEquals('12345', $results[0]['place_id']);
        $this->assertEquals('Sydney Opera House, Sydney, Australia', $results[0]['display_name']);
        $this->assertEquals(-33.8567844, $results[0]['lat']);
        $this->assertEquals(151.2152967, $results[0]['lon']);
        $this->assertEquals('theatre', $results[0]['type']);
        $this->assertEquals('amenity', $results[0]['class']);
        $this->assertEquals(0.751, $results[0]['importance']);

        // Test address formatting
        $this->assertEquals('1', $results[0]['address']['house_number']);
        $this->assertEquals('Macquarie Street', $results[0]['address']['road']);
        $this->assertEquals('Sydney', $results[0]['address']['suburb']);
        $this->assertEquals('Australia', $results[0]['address']['country']);
    }

    public function test_search_returns_empty_array_when_no_results()
    {
        Http::fake([
            'https://eu1.locationiq.com/v1/search.php*' => Http::response([], 200),
        ]);

        $service = new LocationIQService();
        $results = $service->search('nonexistent location');

        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function test_search_throws_exception_on_api_error()
    {
        Http::fake([
            'https://eu1.locationiq.com/v1/search.php*' => Http::response(['error' => 'Invalid API key'], 401),
        ]);

        $service = new LocationIQService();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('LocationIQ API request failed');

        $service->search('test query');
    }

    public function test_get_place_details_returns_formatted_result()
    {
        $mockResponse = [
            'place_id' => '12345',
            'display_name' => 'Sydney Opera House, Sydney, Australia',
            'lat' => '-33.8567844',
            'lon' => '151.2152967',
            'type' => 'theatre',
            'class' => 'amenity',
            'importance' => 0.751,
            'address' => [
                'road' => 'Macquarie Street',
                'suburb' => 'Sydney',
                'country' => 'Australia',
            ],
            'extratags' => [
                'website' => 'https://www.sydneyoperahouse.com',
                'architect' => 'Jørn Utzon',
            ],
            'namedetails' => [
                'name' => 'Sydney Opera House',
                'name:en' => 'Sydney Opera House',
            ],
        ];

        Http::fake([
            'https://eu1.locationiq.com/v1/details.php*' => Http::response($mockResponse, 200),
        ]);

        $service = new LocationIQService();
        $result = $service->getPlaceDetails('12345');

        $this->assertNotNull($result);
        $this->assertEquals('12345', $result['place_id']);
        $this->assertEquals('Sydney Opera House, Sydney, Australia', $result['display_name']);
        $this->assertArrayHasKey('extratags', $result);
        $this->assertArrayHasKey('namedetails', $result);
        $this->assertEquals('https://www.sydneyoperahouse.com', $result['extratags']['website']);
        $this->assertEquals('Sydney Opera House', $result['namedetails']['name']);
    }

    public function test_get_place_details_returns_null_when_not_found()
    {
        Http::fake([
            'https://eu1.locationiq.com/v1/details.php*' => Http::response(null, 200),
        ]);

        $service = new LocationIQService();
        $result = $service->getPlaceDetails('nonexistent');

        $this->assertNull($result);
    }

    public function test_get_account_info_returns_balance_data()
    {
        $mockResponse = [
            'status' => 'ok',
            'balance' => 9500,
            'bonus' => 500,
        ];

        Http::fake([
            'https://eu1.locationiq.com/v1/balance.php*' => Http::response($mockResponse, 200),
        ]);

        $service = new LocationIQService();
        $result = $service->getAccountInfo();

        $this->assertEquals('ok', $result['status']);
        $this->assertEquals(9500, $result['balance']);
        $this->assertEquals(500, $result['bonus']);
    }

    public function test_search_with_options_includes_country_codes()
    {
        Http::fake([
            'https://eu1.locationiq.com/v1/search.php*' => Http::response([], 200),
        ]);

        $service = new LocationIQService();
        $service->search('test', ['countrycodes' => 'au,us']);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'countrycodes=au%2Cus');
        });
    }

    public function test_search_includes_default_parameters()
    {
        Http::fake([
            'https://eu1.locationiq.com/v1/search.php*' => Http::response([], 200),
        ]);

        $service = new LocationIQService();
        $service->search('test');

        Http::assertSent(function ($request) {
            $url = $request->url();
            return str_contains($url, 'format=json') &&
                str_contains($url, 'limit=10') &&
                str_contains($url, 'addressdetails=1') &&
                str_contains($url, 'extratags=1') &&
                str_contains($url, 'namedetails=1') &&
                str_contains($url, 'key=test-api-key');
        });
    }
}
