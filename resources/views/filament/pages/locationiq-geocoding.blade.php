<x-filament-panels::page>
    <div>
        <div class="space-y-6">
            {{ $this->form }}

            @if (!empty($selectedPlace))
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Location Details
                    </h3>

                    <x-filament::card>
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white text-lg">
                                    {{ $selectedPlace['display_name'] }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Place ID: {{ $selectedPlace['place_id'] }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">Location</h5>
                                    <div class="space-y-1 text-sm">
                                        <p><strong>Coordinates:</strong> {{ $selectedPlace['lat'] }},
                                            {{ $selectedPlace['lon'] }}</p>
                                        <p><strong>Type:</strong> {{ $selectedPlace['type'] }}</p>
                                        <p><strong>Class:</strong> {{ $selectedPlace['class'] }}</p>
                                        <p><strong>Importance:</strong>
                                            {{ number_format($selectedPlace['importance'], 3) }}</p>
                                    </div>
                                </div>

                                @if (!empty($selectedPlace['address']))
                                    <div>
                                        <h5 class="font-medium text-gray-900 dark:text-white mb-2">Address</h5>
                                        <div class="space-y-1 text-sm">
                                            @foreach ($selectedPlace['address'] as $key => $value)
                                                @if (!empty($value))
                                                    <p><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                        {{ $value }}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if (!empty($selectedPlace['extratags']))
                                <div>
                                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">Extra Tags</h5>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach ($selectedPlace['extratags'] as $key => $value)
                                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                <strong>{{ $key }}:</strong> {{ $value }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!empty($selectedPlace['namedetails']))
                                <div>
                                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">Name Details</h5>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach ($selectedPlace['namedetails'] as $key => $value)
                                            <span class="text-xs bg-blue-100 dark:bg-blue-900 px-2 py-1 rounded">
                                                <strong>{{ $key }}:</strong> {{ $value }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!empty($selectedPlace['bounding_box']))
                                <div>
                                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">Bounding Box</h5>
                                    <p class="text-sm">
                                        South: {{ $selectedPlace['bounding_box'][0] ?? '' }},
                                        North: {{ $selectedPlace['bounding_box'][1] ?? '' }},
                                        West: {{ $selectedPlace['bounding_box'][2] ?? '' }},
                                        East: {{ $selectedPlace['bounding_box'][3] ?? '' }}
                                    </p>
                                </div>
                            @endif

                            <div class="flex space-x-2 pt-4 border-t">
                                <a href="https://www.google.com/maps?q={{ $selectedPlace['lat'] }},{{ $selectedPlace['lon'] }}"
                                    target="_blank"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    View in Google Maps
                                </a>
                            </div>
                        </div>
                    </x-filament::card>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
