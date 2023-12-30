<?php

namespace App\Filament\Mechanic\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\BikeType;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CustomerBike;
use Filament\Resources\Resource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Filament\Mechanic\Resources\CustomerBikeResource\Pages;

class CustomerBikeResource extends Resource
{
    protected static ?string $model = CustomerBike::class;

    protected static ?string $navigationIcon = 'icon-bike';

    protected static ?string $navigationLabel = 'Tweewielers van klanten';

    protected static ?string $title = 'Tweewielers';

    protected static ?string $slug = 'tweewielers-van-klanten';

    protected static ?string $label = 'Tweewieler';

    protected static ?string $pluralModelLabel = 'Tweewielers van klanten';

    protected static ?string $navigationGroup = 'Servicebeheer';

    protected static ?string $tenantOwnershipRelationshipName = 'servicePoints';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('owner_id')
                        ->relationship('owner', 'name')
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->label('Eigenaar'),
                    Forms\Components\TextInput::make('identifier')
                        ->required()
                        ->maxLength(255)
                        ->label('Kenteken / Serienummer'),
                    Forms\Components\TextInput::make('brand')
                        ->required()
                        ->maxLength(255)
                        ->label('Merk'),
                    Forms\Components\TextInput::make('model')
                        ->required()
                        ->maxLength(255)
                        ->label('Model'),
                    Forms\Components\Select::make('type')
                        ->native(false)
                        ->options(BikeType::class)
                        ->required()
                        ->searchable()
                        ->label('Type voertuig'),
                    Forms\Components\TextInput::make('color')
                        ->required()
                        ->maxLength(255)
                        ->label('Kleur'),
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('asset-images')
                        ->imageEditor()
                        ->label('Afbeelding')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('specifications')
                        ->label('Specificaties')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])
                ->icon('icon-bike')
                ->columns(2)
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                ->circular()
                ->label('Afbeelding')
                ->defaultImageUrl(url('/images/logo.png'))
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('servicePoints.name')
                ->label('Servicepunt')
                ->badge()
                ->color('undefined'),
            Tables\Columns\TextColumn::make('owner.name')
                ->label('Eigenaar')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('identifier')
                ->label('Kenteken')
                ->searchable(),
            Tables\Columns\TextColumn::make('brand')
                ->label('Merk')
                ->searchable(),
            Tables\Columns\TextColumn::make('model')
                ->label('Model')
                ->searchable(),
            Tables\Columns\TextColumn::make('type')
                ->label('Type voertuig')
                ->badge()
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
                Tables\Actions\DeleteAction::make()
                ->before(function (CustomerBike $record) {
                    // Deleting the image from the server when Vehicle $record gets deleted.
                    Storage::delete('public/asset-images/'. $record->image);
                }),
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
            return (string) static::getModel()::count();
        } catch (QueryException $e) {
            return '0';
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerBikes::route('/'),
            'create' => Pages\CreateCustomerBike::route('/create'),
            'edit' => Pages\EditCustomerBike::route('/{record}/edit'),
        ];
    }
}
