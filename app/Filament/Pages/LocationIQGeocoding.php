<?php

namespace App\Filament\Pages;

use App\Services\LocationIQService;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;

class LocationIQGeocoding extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static string $view = 'filament.pages.locationiq-geocoding';
    protected static ?string $navigationGroup = 'API Integration';
    protected static ?string $title = 'LocationIQ Geocoding';
    protected static ?string $navigationLabel = 'LocationIQ Geocoding';
    protected static ?int $navigationSort = 2;

    // Form data
    public ?array $data = [];

    // Results
    public ?array $selectedPlace = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Location Search')
                    ->description('Type to search for addresses and places worldwide')
                    ->schema([
                        Select::make('selected_location')
                            ->label('Search for a Location')
                            ->placeholder('Type an address, place name, or landmark...')
                            ->searchable()
                            ->getSearchResultsUsing(fn(string $search): array => $this->searchAddresses($search))
                            ->getOptionLabelUsing(fn($value): ?string => $this->getLocationLabel($value))
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                if ($state) {
                                    $this->selectLocation($state);
                                }
                            })
                            ->helperText('Start typing to see location suggestions'),

                        Actions::make([
                            Action::make('clearResults')
                                ->label('Clear Results')
                                ->icon('heroicon-o-trash')
                                ->color('gray')
                                ->action('clearResults')
                                ->visible(fn() => !empty($this->selectedPlace)),
                        ])->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function searchAddresses(string $search): array
    {
        if (strlen($search) < 3) {
            return [];
        }

        try {
            $service = new LocationIQService();
            $results = $service->search($search, ['limit' => 10]);

            $options = [];
            foreach ($results as $result) {
                $options[$result['place_id']] = $result['display_name'];
            }

            // Cache results for getLocationLabel method
            session(['locationiq_search_cache' => $results]);

            return $options;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getLocationLabel($value): ?string
    {
        if (!$value) return null;

        $cachedResults = session('locationiq_search_cache', []);

        foreach ($cachedResults as $result) {
            if ($result['place_id'] === $value) {
                return $result['display_name'];
            }
        }

        return $value;
    }

    public function selectLocation(string $placeId): void
    {
        try {
            $service = new LocationIQService();
            $this->selectedPlace = $service->getPlaceDetails($placeId);

            if ($this->selectedPlace) {
                Notification::make()
                    ->title('Location selected')
                    ->body('Detailed information loaded for: ' . $this->selectedPlace['display_name'])
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to load location details')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function clearResults(): void
    {
        $this->selectedPlace = null;
        $this->form->fill([]);
        session()->forget('locationiq_search_cache');

        Notification::make()
            ->title('Results cleared')
            ->success()
            ->send();
    }

    public function openInMaps(float $lat, float $lon): void
    {
        $url = "https://www.google.com/maps?q={$lat},{$lon}";

        Notification::make()
            ->title('Opening in Google Maps')
            ->body('Click the link to open in Google Maps: ' . $url)
            ->info()
            ->send();
    }
}
