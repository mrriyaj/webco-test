<div class="space-y-4">
    @if (empty($addresses))
        <div class="text-center py-8">
            <x-heroicon-o-map-pin class="w-12 h-12 text-gray-400 mx-auto mb-4" />
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No addresses found</h3>
            <p class="text-gray-500 dark:text-gray-400">Search for addresses using the form above.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($addresses as $address)
                <div
                    class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors {{ $selectedAddressId === $address['DirectoryIdentifier'] ? 'ring-2 ring-primary-500 bg-primary-50 dark:bg-primary-900/20' : '' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <x-heroicon-o-building-office-2 class="w-5 h-5 text-gray-400" />
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $address['Address'] }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Directory ID: {{ $address['DirectoryIdentifier'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            @if ($selectedAddressId === $address['DirectoryIdentifier'])
                                <x-filament::badge color="success" icon="heroicon-o-check">
                                    Selected
                                </x-filament::badge>
                            @endif

                            <x-filament::button wire:click="selectAddress('{{ $address['DirectoryIdentifier'] }}')"
                                color="{{ $selectedAddressId === $address['DirectoryIdentifier'] ? 'success' : 'primary' }}"
                                size="sm"
                                icon="{{ $selectedAddressId === $address['DirectoryIdentifier'] ? 'heroicon-o-check' : 'heroicon-o-cursor-arrow-rays' }}">
                                {{ $selectedAddressId === $address['DirectoryIdentifier'] ? 'Selected' : 'Select' }}
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mt-4">
            <div class="flex items-start space-x-3">
                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-500 mt-0.5" />
                <div>
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Found {{ count($addresses) }} address{{ count($addresses) !== 1 ? 'es' : '' }}
                    </h4>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                        Select an address to proceed with service qualification check.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
