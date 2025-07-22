<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Filament\Resources\ProductCategoryResource\RelationManagers;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Product Management';

    protected static ?string $modelLabel = 'Product Category';

    protected static ?string $pluralModelLabel = 'Product Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50)
                    ->label('Category Name')
                    ->placeholder('Enter category name'),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->placeholder('Enter category description')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('external_url')
                    ->label('External URL')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://example.com')
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Category Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(60)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 60 ? $state : null;
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('external_url')
                    ->label('External URL')
                    ->limit(40)
                    ->url(fn($record) => $record->external_url)
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-globe-alt')
                    ->color('primary'),

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
                Tables\Filters\Filter::make('has_url')
                    ->label('Has External URL')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('external_url')),

                Tables\Filters\Filter::make('no_url')
                    ->label('No External URL')
                    ->query(fn(Builder $query): Builder => $query->whereNull('external_url')),
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
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}
