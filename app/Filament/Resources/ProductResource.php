<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Forms\Components\LivewirePriceField;
use App\Jobs\ProcessProductJob;
use App\Jobs\ExportProductJob;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Product Name')
                    ->placeholder('Enter product name')
                    ->columnSpanFull(),

                Forms\Components\Select::make('product_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Textarea::make('description')
                            ->rows(3),
                        Forms\Components\TextInput::make('external_url')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        return ProductCategory::create($data)->getKey();
                    }),

                Forms\Components\Select::make('product_color_id')
                    ->label('Color')
                    ->relationship('color', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn(ProductColor $record) => $record->name . ' (' . $record->hex_code . ')')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Textarea::make('description')
                            ->rows(3),
                        Forms\Components\TextInput::make('hex_code')
                            ->maxLength(8)
                            ->placeholder('#FF0000'),
                        Forms\Components\ColorPicker::make('hex_code')
                            ->label('Color Picker'),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        if ($data['hex_code'] && !str_starts_with($data['hex_code'], '#')) {
                            $data['hex_code'] = '#' . $data['hex_code'];
                        }
                        return ProductColor::create($data)->getKey();
                    }),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->placeholder('Enter product description')
                    ->columnSpanFull(),

                LivewirePriceField::make('price')
                    ->label('Price')
                    ->required()
                    ->helperText('Price will be validated with external service'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Custom Status Bar
                \App\Infolists\Components\ProductStatusBar::make('status_bar')
                    ->label('Product Status')
                    ->message('Hello')
                    ->columnSpanFull(),

                Infolists\Components\Section::make('Product Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Product Name')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('price')
                            ->label('Price')
                            ->money('USD')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('success')
                            ->icon('heroicon-m-currency-dollar'),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Product Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('category.name')
                            ->label('Category')
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-m-tag'),

                        Infolists\Components\TextEntry::make('color.name')
                            ->label('Color Name')
                            ->badge()
                            ->color(fn($record) => $record->color?->hex_code ?? 'gray'),

                        Infolists\Components\ColorEntry::make('color.hex_code')
                            ->label('Color Preview')
                            ->copyable()
                            ->copyMessage('Hex code copied!')
                            ->copyMessageDuration(1500),

                        Infolists\Components\TextEntry::make('color.hex_code')
                            ->label('Hex Code')
                            ->copyable()
                            ->copyMessage('Hex code copied!')
                            ->copyMessageDuration(1500)
                            ->formatStateUsing(fn($state) => $state ?? 'N/A'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Additional Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('category.description')
                            ->label('Category Description')
                            ->default('No description available')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('category.external_url')
                            ->label('Category External URL')
                            ->url(fn($record) => $record->category?->external_url)
                            ->openUrlInNewTab()
                            ->icon('heroicon-m-globe-alt')
                            ->color('primary')
                            ->default('No external URL')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Infolists\Components\Section::make('Timestamps')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime()
                            ->icon('heroicon-m-plus-circle'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime()
                            ->icon('heroicon-m-pencil-square'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('color.name')
                    ->label('Color')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => $record->color?->hex_code ?? 'gray'),

                Tables\Columns\ColorColumn::make('color.hex_code')
                    ->label('Color Preview'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 40 ? $state : null;
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('product_color_id')
                    ->label('Color')
                    ->relationship('color', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('process')
                    ->label('Process Product')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Process Product')
                    ->modalDescription('This will queue a job to process this product. The product description will be updated with processing information.')
                    ->modalSubmitActionLabel('Process')
                    ->action(function (Product $record) {
                        // Dispatch the job
                        ProcessProductJob::dispatch($record, Auth::user());

                        // Show immediate notification
                        Notification::make()
                            ->title('Job Queued Successfully!')
                            ->body("Processing job for '{$record->name}' has been queued. You will receive a notification when it's complete.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TypeAssignmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
