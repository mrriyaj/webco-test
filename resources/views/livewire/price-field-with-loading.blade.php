<div class="w-full">
    <div class="relative">
        <!-- Prefix $ symbol -->
        <div class="absolute left-0 inset-y-0 flex items-center pl-3 pointer-events-none">
            <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">$</span>
        </div>

        <!-- Input field -->
        <input type="number" step="0.01" min="0.01" wire:model.live.debounce.500ms="value"
            @class([
                'block w-full h-11 pl-8 pr-12 text-sm rounded-lg border transition-colors duration-200',
                'bg-white dark:bg-gray-900',
                'text-gray-900 dark:text-gray-100',
                'placeholder-gray-400 dark:placeholder-gray-500',
                'border-red-500 dark:border-red-400 focus:ring-2 focus:ring-red-500/20 dark:focus:ring-red-400/20 focus:border-red-500 dark:focus:border-red-400' =>
                    $isValid === false,
                'border-green-500 dark:border-green-400 focus:ring-2 focus:ring-green-500/20 dark:focus:ring-green-400/20 focus:border-green-500 dark:focus:border-green-400' =>
                    $isValid === true,
                'border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 focus:border-blue-500 dark:focus:border-blue-400' =>
                    $isValid === null,
            ]) placeholder="{{ $placeholder }}" name="{{ $fieldName }}" />

        <!-- Suffix with loading/validation icons -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            @if ($isLoading)
                <!-- Loading spinner -->
                <div wire:loading.delay wire:target="value">
                    <svg class="animate-spin h-5 w-5 text-blue-500 dark:text-blue-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 718-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
            @elseif ($isValid === true)
                <!-- Success icon -->
                <svg class="h-5 w-5 text-green-500 dark:text-green-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            @elseif ($isValid === false)
                <!-- Error icon -->
                <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            @else
                <!-- Default currency icon -->
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                    </path>
                </svg>
            @endif
        </div>
    </div>

    <!-- Validation message -->
    @if ($validationMessage)
        <div class="mt-2">
            <p @class([
                'text-xs',
                'text-blue-600 dark:text-blue-400' => $isLoading,
                'text-green-600 dark:text-green-400' => $isValid === true,
                'text-red-600 dark:text-red-400' => $isValid === false,
                'text-gray-600 dark:text-gray-400' => $isValid === null && !$isLoading,
            ])>
                {{ $validationMessage }}
            </p>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Livewire hack: Listen for validation events and create async behavior
        Livewire.on('validate-price-async', (data) => {
            // Simulate async by dispatching with setTimeout
            setTimeout(() => {
                @this.call('validatePriceAsync', data[0]);
            }, 100); // Small delay to ensure loading state shows
        });
    });
</script>
