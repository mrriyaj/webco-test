<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Modelable;

class PriceFieldWithLoading extends Component
{
    #[Modelable]
    public $value = '';

    public $isLoading = false;
    public $isValid = null;
    public $validationMessage = '';
    public $fieldName = 'price';
    public $label = 'Price';
    public $required = false;
    public $placeholder = '0.00';

    public function mount($value = '', $fieldName = 'price', $label = 'Price', $required = false, $placeholder = '0.00')
    {
        $this->value = $value;
        $this->fieldName = $fieldName;
        $this->label = $label;
        $this->required = $required;
        $this->placeholder = $placeholder;
    }

    public function updatedValue()
    {
        // Emit the value to parent component (Filament form)
        $this->dispatch('input', $this->value);

        if (empty($this->value) || $this->value <= 0) {
            $this->reset(['isLoading', 'validationMessage', 'isValid']);
            return;
        }

        $this->validatePriceWithExternalAPI();
    }

    public function validatePriceWithExternalAPI()
    {
        $this->isLoading = true;
        $this->validationMessage = 'Validating price...';
        $this->isValid = null;

        // Livewire hack: Dispatch to self with delay for background processing
        $this->dispatch('validate-price-async', ['price' => $this->value]);
    }

    #[\Livewire\Attributes\On('validate-price-async')]
    public function validatePriceAsync($data)
    {
        // This creates the async effect - the loading state is already shown
        $this->performExternalValidation($data['price']);
    }

    public function performExternalValidation($price = null)
    {
        $priceToValidate = $price ?? $this->value;

        try {
            // Simulate external API call with delay
            sleep(2); // 2 second delay for demo

            // Mock external API response
            $response = $this->callExternalPriceAPI($priceToValidate);

            if ($response['success']) {
                $this->isValid = $response['valid'];
                $this->validationMessage = $response['message'];
            } else {
                $this->isValid = false;
                $this->validationMessage = 'External service unavailable';
            }
        } catch (\Exception $e) {
            $this->isValid = false;
            $this->validationMessage = 'Validation service error';
        }

        $this->isLoading = false;

        // Emit event to parent component
        $this->dispatch('price-validated', [
            'price' => $priceToValidate,
            'valid' => $this->isValid,
            'message' => $this->validationMessage
        ]);
    }

    private function callExternalPriceAPI($price)
    {
        // Simulate external API call
        $numericPrice = (float) $price;

        // Mock validation rules
        if ($numericPrice < 1) {
            return [
                'success' => true,
                'valid' => false,
                'message' => 'Price must be at least $1.00'
            ];
        }

        if ($numericPrice > 10000) {
            return [
                'success' => true,
                'valid' => false,
                'message' => 'Price exceeds maximum limit ($10,000)'
            ];
        }

        // Random validation for demo
        $isValid = $numericPrice % 2 == 0; // Even prices are "valid" for demo

        return [
            'success' => true,
            'valid' => $isValid,
            'message' => $isValid ? 'Price validated successfully' : 'Price failed external validation'
        ];
    }

    public function render()
    {
        return view('livewire.price-field-with-loading');
    }
}
