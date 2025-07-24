<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\ProductType;
use App\Models\TypeAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TypeAssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'typeAssignments';

    protected static ?string $title = 'Type Assignments';

    protected static ?string $modelLabel = 'Type Assignment';

    protected static ?string $pluralModelLabel = 'Type Assignments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type_id')
                    ->label('Product Type')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Type Name'),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        return ProductType::create($data)->getKey();
                    }),

                Forms\Components\TextInput::make('my_bonus_field')
                    ->label('Bonus Field')
                    ->maxLength(255)
                    ->required()
                    ->placeholder('Enter bonus information'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type.name')
            ->columns([
                Tables\Columns\TextColumn::make('type.name')
                    ->label('Product Type')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type.api_unique_number')
                    ->label('API Number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('my_bonus_field')
                    ->label('Bonus Field')
                    ->searchable()
                    ->placeholder('No bonus field'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Assigned At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type_id')
                    ->label('Product Type')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Assign Product Type')
                    ->modalDescription('Assign a product type to this product with additional information.')
                    ->modalSubmitActionLabel('Assign Type'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Type Assignment')
                    ->modalSubmitActionLabel('Update Assignment'),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Remove Type Assignment')
                    ->modalDescription('Are you sure you want to remove this type assignment?')
                    ->modalSubmitActionLabel('Remove Assignment'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Remove Selected Type Assignments')
                        ->modalDescription('Are you sure you want to remove the selected type assignments?')
                        ->modalSubmitActionLabel('Remove Assignments'),
                ]),
            ])
            ->emptyStateHeading('No type assignments yet')
            ->emptyStateDescription('Start by assigning a product type to this product.')
            ->emptyStateIcon('heroicon-o-tag');
    }
}
