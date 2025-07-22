<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductColorResource\Pages;
use App\Filament\Resources\ProductColorResource\RelationManagers;
use App\Models\ProductColor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductColorResource extends Resource
{
    protected static ?string $model = ProductColor::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'Product Management';

    protected static ?string $modelLabel = 'Color';

    protected static ?string $pluralModelLabel = 'Colors';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50)
                    ->label('Color Name'),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('hex_code')
                    ->label('Hex Code')
                    ->maxLength(8)
                    ->placeholder('#FF0000')
                    ->suffixIcon('heroicon-m-swatch')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set) {
                        // Ensure hex code starts with #
                        if ($state && !str_starts_with($state, '#')) {
                            $set('hex_code', '#' . $state);
                        }
                    }),

                Forms\Components\ColorPicker::make('hex_code')
                    ->label('Color Picker'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Color Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('hex_code')
                    ->label('Hex Code')
                    ->badge()
                    ->color(fn(string $state): string => $state ?: 'gray'),

                Tables\Columns\ColorColumn::make('hex_code')
                    ->label('Color Preview'),

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
                //
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
            'index' => Pages\ListProductColors::route('/'),
            'create' => Pages\CreateProductColor::route('/create'),
            'edit' => Pages\EditProductColor::route('/{record}/edit'),
        ];
    }
}
