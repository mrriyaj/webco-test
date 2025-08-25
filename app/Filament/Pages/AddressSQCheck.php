<?php

namespace App\Filament\Pages;

use App\Services\AddressSQService;
use Exception;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AddressSQCheck extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static string $view = 'filament.pages.address-sq-check';
    protected static ?string $title = 'Address SQ Check';
    protected static ?string $navigationLabel = 'Address SQ Check';
    protected static ?string $navigationGroup = 'API Integration';
    protected static ?int $navigationSort = 1;

    public ?array $data = [];
    public ?array $foundAddresses = [];
    public ?array $qualificationResults = [];
    public ?string $selectedAddressId = null;
    public array $addressCache = []; // Cache for storing address labels

    protected ?AddressSQService $addressService = null;

    public function mount(): void
    {
        $this->addressService = new AddressSQService();
        $this->form->fill();
    }

    protected function getAddressService(): AddressSQService
    {
        if ($this->addressService === null) {
            $this->addressService = new AddressSQService();
        }

        return $this->addressService;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Address Search & Qualification')
                    ->description('Search for an address and check service qualification.')
                    ->schema([
                        Select::make('selected_address')
                            ->label('Search Address')
                            ->placeholder('Type street number, name, suburb, or postcode...')
                            ->searchable()
                            ->getSearchResultsUsing(fn(string $search): array => $this->searchAddresses($search))
                            ->getOptionLabelUsing(fn($value): ?string => $this->getAddressLabel($value))
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedAddressId = $state;
                                $this->qualificationResults = [];
                            })
                            ->helperText('Enter at least 3 characters to search for addresses')
                            ->required(),

                        Actions::make([
                            Action::make('qualify')
                                ->label('Check Service Qualification')
                                ->icon('heroicon-o-check-circle')
                                ->color('success')
                                ->action('qualifyAddress')
                                ->visible(fn() => !empty($this->selectedAddressId)),
                        ]),
                    ])
                    ->columns(1),

                Section::make('Service Qualification Results')
                    ->description('Detailed service qualification information for the selected address.')
                    ->schema([
                        ViewField::make('qualification_results')
                            ->view('filament.components.qualification-results')
                            ->viewData([
                                'results' => $this->qualificationResults,
                            ]),
                    ])
                    ->visible(fn() => !empty($this->qualificationResults))
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function searchAddresses(string $search): array
    {
        if (strlen($search) < 3) {
            return [];
        }

        try {
            // Parse the search string to extract address components
            $addressParts = $this->parseSearchString($search);

            $addresses = $this->getAddressService()->findAddress($addressParts);

            // Convert to options array for select component and cache labels
            $options = [];
            foreach ($addresses as $address) {
                $directoryId = $address['DirectoryIdentifier'];
                $addressLabel = $address['Address'];
                $options[$directoryId] = $addressLabel;

                // Cache the label for later retrieval
                $this->addressCache[$directoryId] = $addressLabel;
            }

            return $options;
        } catch (Exception $e) {
            // Log error but don't show notification during search
            Log::error('Address search failed', [
                'search' => $search,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    public function getAddressLabel(string $directoryIdentifier): ?string
    {
        // Return cached label if available
        return $this->addressCache[$directoryIdentifier] ?? $directoryIdentifier;
    }

    private function parseSearchString(string $search): array
    {
        $search = trim($search);

        // Try to extract components from the search string
        // This is a simple parser - you can make it more sophisticated
        $parts = explode(' ', $search);

        $streetNumber = '';
        $streetName = '';
        $streetType = '';
        $suburb = '';
        $postcode = '';
        $state = 'VIC'; // Default state

        // Look for postcode (4 digits)
        foreach ($parts as $part) {
            if (preg_match('/^\d{4}$/', $part)) {
                $postcode = $part;
                break;
            }
        }

        // Look for street number (numbers at the beginning)
        if (preg_match('/^\d+/', $parts[0])) {
            $streetNumber = $parts[0];
            array_shift($parts);
        }

        // Look for common street types
        $streetTypes = array_keys(AddressSQService::getStreetTypes());
        foreach ($parts as $index => $part) {
            foreach ($streetTypes as $type) {
                if (strcasecmp($part, $type) === 0 || strcasecmp($part, substr($type, 0, 2)) === 0) {
                    $streetType = $type;
                    unset($parts[$index]);
                    break 2;
                }
            }
        }

        // Remove postcode from parts if found
        $parts = array_filter($parts, fn($part) => $part !== $postcode);

        // The remaining parts are likely street name and suburb
        if (count($parts) > 0) {
            if ($streetType && count($parts) > 1) {
                // If we have street type, split remaining into street name and suburb
                $streetName = $parts[0];
                $suburb = implode(' ', array_slice($parts, 1));
            } else {
                // Use the first part as street name, rest as suburb
                $streetName = array_shift($parts);
                $suburb = implode(' ', $parts);
            }
        }

        // Set defaults if not found
        if (!$streetType) $streetType = 'Street';
        if (!$streetName) $streetName = $search;
        if (!$suburb) $suburb = $search;
        if (!$postcode) $postcode = '3000'; // Default Melbourne postcode

        return [
            'street_number' => $streetNumber ?: null,
            'street_name' => $streetName,
            'street_type' => $streetType,
            'suburb' => $suburb,
            'postcode' => $postcode,
            'state' => $state,
        ];
    }

    public function selectAddress(string $directoryIdentifier): void
    {
        $this->selectedAddressId = $directoryIdentifier;
        $this->qualificationResults = [];

        Notification::make()
            ->title('Address selected')
            ->body('Address selected for qualification.')
            ->info()
            ->send();
    }

    public function qualifyAddress(): void
    {
        if (empty($this->selectedAddressId)) {
            Notification::make()
                ->title('No address selected')
                ->body('Please select an address first.')
                ->warning()
                ->send();
            return;
        }

        try {
            $results = $this->getAddressService()->qualifyService($this->selectedAddressId);

            $this->qualificationResults = $results;

            if (isset($results['result']) && $results['result'] === 'Success') {
                Notification::make()
                    ->title('Qualification successful')
                    ->body($results['message'] ?? 'Service qualification completed successfully.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Qualification completed')
                    ->body('Service qualification completed. Please review the results.')
                    ->info()
                    ->send();
            }
        } catch (Exception $e) {
            Notification::make()
                ->title('Qualification failed')
                ->body('An error occurred during service qualification: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function clearResults(): void
    {
        $this->selectedAddressId = null;
        $this->qualificationResults = [];
        $this->addressCache = [];
        $this->data['selected_address'] = null;

        Notification::make()
            ->title('Results cleared')
            ->body('Search and qualification results have been cleared.')
            ->info()
            ->send();
    }
}
