<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\BikeType;
use App\Enums\UserRoles;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CustomerBike;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\CustomerBikeResource\Pages;

class CustomerBikeResource extends Resource
{
    protected static ?string $model = CustomerBike::class;

    protected static ?string $navigationIcon = 'icon-customer-bike';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'identifier';

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
                        ->label(__('filament.owner'))
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label(__('filament.name'))
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('contact_phone')
                                ->label(__('filament.phone'))
                                ->tel()
                                ->maxLength(255)
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('contact_email')
                                ->label(__('filament.contact_email'))
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('alternate_contact_email')
                                ->label(__('filament.alternate_contact_email'))
                                ->email()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('website')
                                ->label(__('filament.website'))
                                ->maxLength(255),
                            Forms\Components\TextInput::make('street_address')
                                ->label(__('filament.street_address'))
                                ->maxLength(255),
                            Forms\Components\TextInput::make('city')
                                ->label(__('filament.city'))
                                ->maxLength(255),
                            Forms\Components\TextInput::make('province')
                                ->label(__('filament.province'))
                                ->maxLength(255),
                            Forms\Components\TextInput::make('postal_code')
                                ->label(__('filament.postal_code'))
                                ->maxLength(255),
                        ]),
                    Forms\Components\Select::make('service_point_id')
                        ->relationship('servicePoints', 'name')
                        ->label(__('filament.service_points.plural_label'))
                        ->native(false)
                        ->multiple()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('identifier')
                        ->required()
                        ->maxLength(255)
                        ->label(__('filament.identifier')),
                    Forms\Components\TextInput::make('brand')
                        ->required()
                        ->maxLength(255)
                        ->label(__('filament.brand')),
                    Forms\Components\TextInput::make('model')
                        ->required()
                        ->maxLength(255)
                        ->label(__('filament.model')),
                    Forms\Components\Select::make('type')
                        ->native(false)
                        ->options(BikeType::class)
                        ->required()
                        ->searchable()
                        ->label(__('filament.type')),
                    Forms\Components\TextInput::make('color')
                        ->required()
                        ->maxLength(255)
                        ->label(__('filament.color')),
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('asset-images')
                        ->imageEditor()
                        ->label(__('filament.image'))
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('specifications')
                        ->label(__('filament.specifications'))
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
                    ->label(__('filament.image'))
                    ->defaultImageUrl(url('/images/logo.png'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('servicePoints.name')
                    ->label(__('filament.service_points.plural_label'))
                    ->badge()
                    ->color('undefined'),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label(__('filament.owner'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('identifier')
                    ->label(__('filament.identifier'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label(__('filament.brand'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->label(__('filament.model'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.type'))
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label(__('filament.color'))
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
                Tables\Filters\SelectFilter::make('name')
                    ->label(__('filament.owner'))
                    ->relationship('owner', 'name', function (Builder $query) {
                        $query->where('role_id', UserRoles::Customer);
                    })
                    ->preload()
                    ->native(false)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('service_point_id')
                    ->label(__('filament.service_points.plural_label'))
                    ->multiple()
                    ->preload()
                    ->native(false)
                    ->relationship('servicePoints', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (CustomerBike $record) {
                        // Deleting the image from the server when Vehicle $record gets deleted.
                        Storage::delete('public/asset-images/' . $record->image);
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
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerBikes::route('/'),
            'create' => Pages\CreateCustomerBike::route('/create'),
            'edit' => Pages\EditCustomerBike::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('filament.customer_bikes.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.customer_bikes.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.customer_bikes.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.customer_bikes.navigation_group');
    }

    public static function getTitle(): string
    {
        return __('filament.customer_bikes.title');
    }

    public static function getSlug(): string
    {
        return __('filament.customer_bikes.slug');
    }
}
