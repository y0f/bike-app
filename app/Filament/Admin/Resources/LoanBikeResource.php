<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\BikeType;
use App\Models\LoanBike;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\LoanBikeStatus;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\LoanBikeResource\Pages;
use App\Filament\Admin\Resources\ServicePointResource\RelationManagers\LoanBikesRelationManager;

class LoanBikeResource extends Resource
{
    protected static ?string $model = LoanBike::class;

    protected static ?string $navigationIcon = 'icon-loan-bike';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_point_id')
                    ->relationship('servicePoint', 'name')
                    ->label(__('filament.service_points.label'))
                    ->required()
                    ->hiddenOn(LoanBikesRelationManager::class)
                    ->disabledOn(LoanBikesRelationManager::class),
                Forms\Components\Select::make('status')
                    ->label(__('filament.loan_bikes.status'))
                    ->required()
                    ->native(false)
                    ->options(LoanBikeStatus::class)
                    ->default('available'),
                Forms\Components\TextInput::make('identifier')
                    ->label(__('filament.loan_bikes.identifier'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('brand')
                    ->label(__('filament.loan_bikes.brand'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
                    ->label(__('filament.loan_bikes.model'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label(__('filament.loan_bikes.type'))
                    ->native(false)
                    ->options(BikeType::class)
                    ->required()
                    ->searchable(),
                Forms\Components\FileUpload::make('image')
                    ->label(__('filament.loan_bikes.image'))
                    ->image(),
                Forms\Components\TextInput::make('color')
                    ->label(__('filament.loan_bikes.color'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('specifications')
                    ->label(__('filament.loan_bikes.specifications'))
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('servicePoint.name')
                    ->label(__('filament.service_points.label'))
                    ->sortable()
                    ->hiddenOn(LoanBikesRelationManager::class),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.loan_bikes.status'))
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('identifier')
                    ->label(__('filament.loan_bikes.identifier'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label(__('filament.loan_bikes.brand'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->label(__('filament.loan_bikes.model'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.loan_bikes.type'))
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label(__('filament.loan_bikes.color'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.loan_bikes.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament.loan_bikes.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoanBikes::route('/'),
            'create' => Pages\CreateLoanBike::route('/create'),
            'edit' => Pages\EditLoanBike::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('filament.loan_bikes.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.loan_bikes.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.loan_bikes.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.loan_bikes.navigation_group');
    }

    public static function getTitle(): string
    {
        return __('filament.loan_bikes.title');
    }

    public static function getSlug(): string
    {
        return __('filament.loan_bikes.slug');
    }
}
