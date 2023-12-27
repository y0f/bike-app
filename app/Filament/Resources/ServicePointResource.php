<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ServicePoint;
use Filament\Resources\Resource;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServicePointResource\Pages;
use App\Filament\Resources\ServicePointResource\RelationManagers;

class ServicePointResource extends Resource
{
    protected static ?string $model = ServicePoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    
    protected static ?string $navigationGroup = 'Bedrijven';

    protected static ?string $navigationLabel = 'Servicepunt';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Naam')
                        ->prefixIcon('heroicon-o-cog-6-tooth')
                        ->prefixIconColor('primary')
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hintColor('primary')
                        ->hintIconTooltip('De naam en de locatie van uw vestiging.')
                        ->placeholder('Repairstation Rosmalen')
                        ->required()
                        ->unique()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->prefixIcon('heroicon-o-phone')
                        ->prefixIconColor('primary')
                        ->label('Telefoonnummer')
                        ->placeholder('062233445566')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('address')
                        ->prefixIcon('heroicon-o-map-pin')
                        ->prefixIconColor('primary')
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hintColor('primary')
                        ->hintIconTooltip('Straatnaam en huisnummer.')
                        ->label('Adres')
                        ->placeholder('Pieterburglaan 12')
                        ->unique()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('zip')
                        ->placeholder('5555AA')
                        ->prefixIcon('heroicon-o-paper-airplane')
                        ->prefixIconColor('primary')
                        ->label('Postcode')
                        ->required()
                        ->maxLength(255),
                ])

                    ->icon('heroicon-o-wrench-screwdriver')
                    ->description('Hier kunt de garage locaties binnen uw bedrijf toevoegen.')
                    ->iconColor('primary')
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Adres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip')
                    ->label('Postcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefoonnummer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
        try {
            return static::getModel()::count();
        } catch (QueryException $e) {
            return 0;
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServicePoints::route('/'),
            'create' => Pages\CreateServicePoint::route('/create'),
            'edit' => Pages\EditServicePoint::route('/{record}/edit'),
        ];
    }
}
