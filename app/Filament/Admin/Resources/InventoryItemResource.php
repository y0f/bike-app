<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InventoryItemResource\Pages;
use App\Models\InventoryItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InventoryItemResource extends Resource
{
    protected static ?string $model = InventoryItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_point_id')
                    ->label(__('filament.service_point_location'))
                    ->relationship('servicePoint', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label(__('filament.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('brand')
                    ->label(__('filament.brand'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->label(__('filament.type'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('stock')
                    ->label(__('filament.stock'))
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('price')
                    ->label(__('filament.price'))
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->prefix('€'),
                Forms\Components\TextInput::make('low_stock_threshold')
                    ->label(__('filament.low_stock_threshold'))
                    ->required()
                    ->numeric()
                    ->default(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TODO: Add import with csv, xlsx
                // TODO: Add transaction model
                Tables\Columns\TextColumn::make('servicePoint.name')
                    ->label(__('filament.service_points.plural_label'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label(__('filament.brand'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label(__('filament.stock'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('filament.price_per_unit'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('low_stock_threshold')
                    ->label(__('filament.low_stock_threshold'))
                    ->numeric()
                    ->sortable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryItems::route('/'),
            'create' => Pages\CreateInventoryItem::route('/create'),
            'edit' => Pages\EditInventoryItem::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('filament.inventory_items.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.inventory_items.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.inventory_items.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.inventory_items.navigation_group');
    }

    public static function getTitle(): string
    {
        return __('filament.inventory_items.title');
    }

    public static function getSlug(): string
    {
        return __('filament.inventory_items.slug');
    }
}
