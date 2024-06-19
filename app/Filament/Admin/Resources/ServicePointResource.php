<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ServicePoint;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\ServicePointResource\Pages;

class ServicePointResource extends Resource
{
    protected static ?string $model = ServicePoint::class;

    protected static ?string $navigationIcon = 'icon-service-point';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label(__('filament.name'))
                        ->prefixIcon('heroicon-o-cog-6-tooth')
                        ->prefixIconColor('primary')
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hintColor('primary')
                        ->hintIconTooltip(__('filament.name_hint'))
                        ->placeholder('Repairstation Rosmalen')
                        ->required()
                        ->unique()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->prefixIcon('heroicon-o-phone')
                        ->prefixIconColor('primary')
                        ->label(__('filament.phone'))
                        ->placeholder('062233445566')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                        ->prefixIcon('heroicon-o-map-pin')
                        ->prefixIconColor('primary')
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hintColor('primary')
                        ->hintIconTooltip(__('filament.address_hint'))
                        ->label(__('filament.address'))
                        ->placeholder('Pieterburglaan 12')
                        ->unique()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('zip')
                        ->placeholder('5555AA')
                        ->prefixIcon('heroicon-o-paper-airplane')
                        ->prefixIconColor('primary')
                        ->label(__('filament.zip'))
                        ->required()
                        ->maxLength(255),
                ])
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->description(__('filament.service_point_description'))
                    ->iconColor('primary')
                    ->columns(2)
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('filament.address'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip')
                    ->label(__('filament.zip'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('filament.phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (ServicePoint $record) {
                        $record->users()->detach();
                        $record->customerBikes()->detach();
                        $record->appointments()->delete();
                        $record->schedules()->delete();
                    }),
            ])
            ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
            'index' => Pages\ListServicePoints::route('/'),
            'create' => Pages\CreateServicePoint::route('/create'),
            'edit' => Pages\EditServicePoint::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('filament.service_points.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.service_points.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.service_points.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.service_points.navigation_group');
    }
}
