<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\BikeType;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CustomerBike;
use Filament\Resources\Resource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerBikeResource\Pages;
use App\Filament\Resources\CustomerBikeResource\RelationManagers;

class CustomerBikeResource extends Resource
{
    protected static ?string $model = CustomerBike::class;

    protected static ?string $navigationIcon = 'icon-bike';

    protected static ?string $navigationLabel = 'Klantfietsen';

    protected static ?string $title = 'fietsen';

    protected static ?string $slug = 'fietsen';

    protected static ?string $label = 'fiets';

    protected static ?string $pluralModelLabel = 'fietsen';

    protected static ?string $navigationGroup = 'Klanten';

    protected static ?int $navigationSort = 5;

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
                        ->label('Eigenaar')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Voornaam & Achternaam')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('contact_phone')
                                ->label('Telefoonnummer')
                                ->tel()
                                ->maxLength(255)
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('contact_email')
                                ->label('Contact email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('alternate_contact_email')
                                ->label('Alternatieve contact email')
                                ->email()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('website')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('street_address')
                                ->label('Adres')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('city')
                                ->label('Stad')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('province')
                                ->label('Provincie')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('postal_code')
                                ->label('Postcode')
                                ->maxLength(255),
                        ]),
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
                    Forms\Components\DatePicker::make('year_build')
                        ->native(false)
                        ->required()
                        ->closeOnDateSelection()
                        ->label('Bouwjaar'),
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
                ->icon('heroicon-o-cog') // Change the icon to a motorcycle or any other suitable icon
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
                ->label('Model') // Update the label for the 'model' column
                ->searchable(),
            Tables\Columns\TextColumn::make('type')
                ->label('Type voertuig')
                ->badge()
                ->searchable(),
            Tables\Columns\TextColumn::make('year_build')
                ->label('Bouwjaar')
                ->date('Y')
                ->sortable(),
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
            return static::getModel()::count();
        } catch (QueryException $e) {
            return 0;
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
