<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}

        @if (!empty($qualificationResults))
            <div class="flex justify-end">
                <x-filament::button wire:click="clearResults" color="gray" icon="heroicon-o-trash" size="sm">
                    Clear Results
                </x-filament::button>
            </div>
        @endif
    </div>
</x-filament-panels::page>
