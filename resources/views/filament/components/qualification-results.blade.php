<div class="space-y-6">
    @if (empty($results))
        <div class="text-center py-8">
            <x-heroicon-o-signal class="w-12 h-12 text-gray-400 mx-auto mb-4" />
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No qualification results</h3>
            <p class="text-gray-500 dark:text-gray-400">Select an address and run qualification to see results.</p>
        </div>
    @else
        <!-- Result Summary -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <div class="flex items-center space-x-3 mb-4">
                <x-heroicon-o-signal class="w-6 h-6 text-primary-500" />
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Qualification Summary</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Result:</span>
                    <x-filament::badge color="{{ $results['result'] === 'Success' ? 'success' : 'danger' }}"
                        class="ml-2">
                        {{ $results['result'] ?? 'Unknown' }}
                    </x-filament::badge>
                </div>
                @if (isset($results['message']))
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Message:</span>
                        <span class="text-sm text-gray-900 dark:text-white ml-2">{{ $results['message'] }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if (isset($results['data']) && is_array($results['data']))
            @foreach ($results['data'] as $index => $qualification)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <x-heroicon-o-wifi class="w-6 h-6 text-green-500" />
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Service Qualification {{ $index + 1 }}
                        </h4>
                    </div>

                    <!-- Service Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        @if (isset($qualification->Result))
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Result</span>
                                <p
                                    class="text-lg font-semibold {{ $qualification->Result === 'PASS' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $qualification->Result }}
                                </p>
                            </div>
                        @endif

                        @if (isset($qualification->ServiceType))
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Service Type</span>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $qualification->ServiceType }}</p>
                            </div>
                        @endif

                        @if (isset($qualification->ServiceClass))
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Service Class</span>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $qualification->ServiceClass }}</p>
                            </div>
                        @endif

                        @if (isset($qualification->ConnectionType))
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Connection
                                    Type</span>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $qualification->ConnectionType }}</p>
                            </div>
                        @endif

                        @if (isset($qualification->Zone))
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Zone</span>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $qualification->Zone }}</p>
                            </div>
                        @endif

                        @if (isset($qualification->CSA))
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">CSA</span>
                                <p class="text-sm font-mono text-gray-900 dark:text-white">{{ $qualification->CSA }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Additional Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @if (isset($qualification->AlternativeTechnology))
                            <div
                                class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Alternative
                                    Technology</span>
                                <x-filament::badge
                                    color="{{ $qualification->AlternativeTechnology === 'TRUE' ? 'success' : 'danger' }}">
                                    {{ $qualification->AlternativeTechnology === 'TRUE' ? 'Available' : 'Not Available' }}
                                </x-filament::badge>
                            </div>
                        @endif

                        @if (isset($qualification->DevelopmentCharge))
                            <div
                                class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <span class="text-sm font-medium text-yellow-700 dark:text-yellow-300">Development
                                    Charge</span>
                                <x-filament::badge
                                    color="{{ $qualification->DevelopmentCharge === 'FALSE' ? 'success' : 'warning' }}">
                                    {{ $qualification->DevelopmentCharge === 'FALSE' ? 'No Charge' : 'Charge Required' }}
                                </x-filament::badge>
                            </div>
                        @endif

                        @if (isset($qualification->CopperDisconnectionDate))
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Copper Disconnection
                                    Date</span>
                                <span class="text-sm font-mono text-gray-900 dark:text-white">
                                    {{ $qualification->CopperDisconnectionDate }}
                                </span>
                            </div>
                        @endif

                        @if (isset($qualification->CVCID))
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">CVC ID</span>
                                <span class="text-sm font-mono text-gray-900 dark:text-white">
                                    {{ $qualification->CVCID }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Copper Pair Records -->
                    @if (isset($qualification->CopperPairRecords) && is_array($qualification->CopperPairRecords))
                        <div class="mt-6">
                            <h5 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <x-heroicon-o-cpu-chip class="w-5 h-5 mr-2" />
                                Copper Pair Records ({{ count($qualification->CopperPairRecords) }})
                            </h5>

                            <div class="space-y-4">
                                @foreach ($qualification->CopperPairRecords as $recordIndex => $record)
                                    @php
                                        $parsedRecord = [];
                                        try {
                                            $xml = simplexml_load_string($record);
                                            if ($xml) {
                                                $parsedRecord = [
                                                    'id' => (string) $xml->CopperPairID,
                                                    'status' => (string) $xml->CopperPairStatus,
                                                    'nbn_status' => (string) $xml->NBNServiceStatus,
                                                    'service_class' => (string) $xml->ServiceClass,
                                                    'tc2_speed' => (string) $xml->TC2Speed,
                                                    'upload_speed' => (string) $xml->UploadSpeed,
                                                    'download_speed' => (string) $xml->DownloadSpeed,
                                                    'network_co_exist' => (string) $xml->NetworkCoExist,
                                                    'extra_charge' => (string) $xml->ExtraCharge,
                                                ];
                                            }
                                        } catch (Exception $e) {
                                            // Handle parsing error
                                        }
                                    @endphp

                                    @if (!empty($parsedRecord))
                                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <h6 class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Pair {{ $recordIndex + 1 }}: {{ $parsedRecord['id'] }}
                                                </h6>
                                                <x-filament::badge
                                                    color="{{ $parsedRecord['status'] === 'Inactive' ? 'warning' : 'success' }}">
                                                    {{ $parsedRecord['status'] }}
                                                </x-filament::badge>
                                            </div>

                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">NBN Status:</span>
                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                        {{ $parsedRecord['nbn_status'] }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Service Class:</span>
                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                        {{ $parsedRecord['service_class'] }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Upload Speed:</span>
                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                        {{ $parsedRecord['upload_speed'] }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Download
                                                        Speed:</span>
                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                        {{ $parsedRecord['download_speed'] }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">TC2 Speed:</span>
                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                        {{ $parsedRecord['tc2_speed'] }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Extra Charge:</span>
                                                    <x-filament::badge
                                                        color="{{ $parsedRecord['extra_charge'] === 'FALSE' ? 'success' : 'warning' }}"
                                                        size="sm">
                                                        {{ $parsedRecord['extra_charge'] === 'FALSE' ? 'No' : 'Yes' }}
                                                    </x-filament::badge>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 dark:text-gray-400">Network
                                                        Co-exist:</span>
                                                    <x-filament::badge
                                                        color="{{ $parsedRecord['network_co_exist'] === 'TRUE' ? 'success' : 'danger' }}"
                                                        size="sm">
                                                        {{ $parsedRecord['network_co_exist'] === 'TRUE' ? 'Yes' : 'No' }}
                                                    </x-filament::badge>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Broadband Address Record -->
                    @if (isset($qualification->BroadbandAddressRecord))
                        <div
                            class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <h5 class="text-sm font-medium text-green-800 dark:text-green-200 mb-2">Broadband Address
                                Record</h5>
                            <div class="text-sm text-green-700 dark:text-green-300 font-mono">
                                {{ $qualification->BroadbandAddressRecord }}
                            </div>
                        </div>
                    @endif

                    <!-- NBN COAT Record -->
                    @if (isset($qualification->NBNCOATRecord))
                        <div
                            class="mt-4 p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                            <h5 class="text-sm font-medium text-purple-800 dark:text-purple-200 mb-2">NBN COAT Record
                            </h5>
                            <div class="text-sm text-purple-700 dark:text-purple-300 font-mono">
                                {{ $qualification->NBNCOATRecord }}
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    @endif
</div>
