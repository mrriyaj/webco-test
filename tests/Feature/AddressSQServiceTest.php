<?php

namespace Tests\Feature;

use App\Services\AddressSQService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AddressSQServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AddressSQService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AddressSQService();
    }

    /** @test */
    public function it_can_get_street_types()
    {
        $streetTypes = AddressSQService::getStreetTypes();

        $this->assertIsArray($streetTypes);
        $this->assertArrayHasKey('Street', $streetTypes);
        $this->assertArrayHasKey('Avenue', $streetTypes);
        $this->assertEquals('Street', $streetTypes['Street']);
    }

    /** @test */
    public function it_can_get_states()
    {
        $states = AddressSQService::getStates();

        $this->assertIsArray($states);
        $this->assertArrayHasKey('VIC', $states);
        $this->assertArrayHasKey('NSW', $states);
        $this->assertEquals('Victoria', $states['VIC']);
    }

    /** @test */
    public function it_can_parse_copper_pair_records()
    {
        $xmlRecords = [
            '<CopperPairRecord><CopperPairID>CPI300012170843</CopperPairID><CopperPairStatus>N/A</CopperPairStatus><NBNServiceStatus>Line In Use</NBNServiceStatus><ServiceClass>34</ServiceClass><POTSInterconnect>N/A</POTSInterconnect><POTSMatch>FALSE</POTSMatch><TC2Speed>5Mbps,10Mbps,20Mbps</TC2Speed><UploadSpeed>38-40</UploadSpeed><DownloadSpeed>95-100</DownloadSpeed><NetworkCoExist>TRUE</NetworkCoExist><ExtraCharge>FALSE</ExtraCharge></CopperPairRecord>'
        ];

        $parsed = $this->service->parseCopperPairRecords($xmlRecords);

        $this->assertIsArray($parsed);
        $this->assertCount(1, $parsed);
        $this->assertEquals('CPI300012170843', $parsed[0]['copper_pair_id']);
        $this->assertEquals('N/A', $parsed[0]['copper_pair_status']);
        $this->assertEquals('Line In Use', $parsed[0]['nbn_service_status']);
        $this->assertEquals('TRUE', $parsed[0]['network_co_exist']);
        $this->assertEquals('FALSE', $parsed[0]['extra_charge']);
    }

    /** @test */
    public function it_handles_authentication_failure_gracefully()
    {
        Http::fake([
            'extranet.asmorphic.com/api/login' => Http::response(['error' => 'Invalid credentials'], 401)
        ]);

        $result = $this->service->authenticate();

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_authenticate_successfully()
    {
        Http::fake([
            'extranet.asmorphic.com/api/login' => Http::response([
                'success' => true,
                'result' => ['token' => 'test-token-123'],
                'message' => 'User login successfully.'
            ], 200)
        ]);

        $result = $this->service->authenticate();

        $this->assertTrue($result);
    }
    /** @test */
    public function it_throws_exception_when_finding_address_without_authentication()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Authentication failed');

        Http::fake([
            'extranet.asmorphic.com/api/login' => Http::response(['error' => 'Invalid credentials'], 401)
        ]);

        $this->service->findAddress([
            'street_name' => 'Collins',
            'street_type' => 'Street',
            'suburb' => 'Melbourne',
            'postcode' => '3000',
            'state' => 'VIC'
        ]);
    }
}
