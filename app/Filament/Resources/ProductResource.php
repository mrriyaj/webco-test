<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
      //
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
