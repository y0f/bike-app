<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\BikeType;
use App\Models\LoanBike;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\LoanBikeStatus;
use Filament\Resources\Resource;
use App\Filament\Resources\LoanBikeResource\Pages;

class LoanBikeResource extends Resource
{
    protected static ?string $model = LoanBike::class;

    protected static ?string $navigationIcon = 'icon-loan-bike';

    protected static ?string $navigationLabel = 'Leenmiddelen';

    protected static ?string $title = 'Leenmiddelen';

    protected static ?string $slug = 'leenmiddelen';

    protected static ?string $label = 'leenmiddel';

    protected static ?string $pluralModelLabel = 'Leenmiddelen';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_point_id')
                    ->relationship('servicePoint', 'name')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->native(false)
                    ->options(LoanBikeStatus::class)
                    ->default('available'),
                Forms\Components\TextInput::make('identifier')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('brand')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->native(false)
                    ->options(BikeType::class)
                    ->required()
                    ->searchable()
                    ->label('Type voertuig'),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\TextInput::make('color')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('specifications')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('servicePoint.name')
                    ->label('Servicepunt')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('identifier')
                    ->label('Kenteken / Serienummer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                ->label('Merk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('Kleur')
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
}
