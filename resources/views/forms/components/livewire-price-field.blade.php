<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="relative">
        @livewire(
            'price-field-with-loading',
            [
                'value' => $getState() ?? '',
                'fieldName' => $getStatePath(),
                'label' => null, // Let Filament handle the label
                'required' => $isRequired(),
                'placeholder' => '0.00',
            ],
            key($getStatePath())
        )
    </div>
</x-dynamic-component>
