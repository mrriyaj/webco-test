<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @php
        $state = $getState();
        $complementaryColor = $state['hex_code'] ?? '#6366f1';
        $originalColor = $state['original_color'] ?? '#6366f1';
        $message = $getMessage() ?? 'Hello';
    @endphp

    <div class="rounded-lg p-4 text-center text-white font-semibold" style="background-color: {{ $complementaryColor }};">
        <div>{{ $message }}</div>
        <div class="text-xs mt-2 opacity-75">
            Background: {{ $complementaryColor }} | Product Color: {{ $originalColor }}
        </div>
    </div>
</x-dynamic-component>
