<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class LivewirePriceField extends Field
{
    protected string $view = 'forms.components.livewire-price-field';

    protected function setUp(): void
    {
        parent::setUp();

        $this->rule('numeric')
            ->rule('min:0.01');
    }
}
