<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTypeResource\Pages;
use App\Filament\Resources\ProductTypeResource\RelationManagers;
use App\Models\ProductType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductTypeResource extends Resource
{
  protected static ?string $model = ProductType::class;

  protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

  protected static ?string $navigationGroup = 'Product Management';

  protected static ?string $modelLabel = 'Type';

  protected static ?string $pluralModelLabel = 'Types';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('name')
          ->required()
          ->maxLength(255)
          ->label('Type Name')
          ->placeholder('Enter product type name'),

        Forms\Components\TextInput::make('api_unique_number')
          ->label('API Unique Number')
          ->maxLength(100)
          ->unique(ignoreRecord: true)
          ->placeholder('AUTO-GENERATED')
          ->suffixIcon('heroicon-m-hashtag')
          ->helperText('Leave blank to auto-generate a unique number')
          ->columnSpanFull(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->label('Type Name')
          ->searchable()
          ->sortable()
          ->weight('bold'),

        Tables\Columns\TextColumn::make('api_unique_number')
          ->label('API Number')
          ->searchable()
          ->sortable()
          ->badge()
          ->color('gray')
          ->copyable()
          ->copyMessage('API number copied!')
          ->copyMessageDuration(1500),

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
        Tables\Filters\Filter::make('has_api_number')
          ->label('Has API Number')
          ->query(fn(Builder $query): Builder => $query->whereNotNull('api_unique_number')),

        Tables\Filters\Filter::make('no_api_number')
          ->label('No API Number')
          ->query(fn(Builder $query): Builder => $query->whereNull('api_unique_number')),
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
        Tables\Actions\Action::make('generate_api_number')
          ->label('Generate API Number')
          ->icon('heroicon-m-arrow-path')
          ->color('success')
          ->visible(fn($record) => empty($record->api_unique_number))
          ->action(function ($record) {
            $record->update([
              'api_unique_number' => 'PT-' . strtoupper(uniqid())
            ]);
          })
          ->successNotificationTitle('API number generated successfully!'),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
          Tables\Actions\BulkAction::make('generate_api_numbers')
            ->label('Generate API Numbers')
            ->icon('heroicon-m-arrow-path')
            ->color('success')
            ->action(function ($records) {
              foreach ($records as $record) {
                if (empty($record->api_unique_number)) {
                  $record->update([
                    'api_unique_number' => 'PT-' . strtoupper(uniqid())
                  ]);
                }
              }
            })
            ->successNotificationTitle('API numbers generated for selected records!'),
        ]),
      ])
      ->defaultSort('name');
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListProductTypes::route('/'),
      'create' => Pages\CreateProductType::route('/create'),
      'edit' => Pages\EditProductType::route('/{record}/edit'),
    ];
  }
}
