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
                        ->label(__('filament.customer_bikes.owner'))
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label(__('filament.customer_bikes.name'))
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('contact_phone')
                                ->label(__('filament.customer_bikes.phone'))
                                ->tel()
                                ->maxLength(255)
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('contact_email')
                                ->label(__('filament.customer_bikes.contact_email'))
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('alternate_contact_email')
                                ->label(__('filament.customer_bikes.alternate_contact_email'))
                                ->email()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('website')
                                ->label(__('filament.customer_bikes.website'))
                                ->maxLength(255),
                            Forms\Components\TextInput::make('street_address')
                                ->label(__('filament.customer_bikes.street_address'))
                                ->maxLength(255),
                            Forms\Components\TextInput::make('city')
                                ->label(__('filament.customer_bikes.city'))
                                ->maxLength(255),
                            Forms\Components\TextInput::make('province')
                                ->label(__('filament.customer_bikes.province'))
                                ->maxLength(255),
                            Forms\Components\TextInput::make('postal_code')
                                ->label(__('filament.customer_bikes.postal_code'))
                                ->maxLength(255),
                        ]),
                    Forms\Components\Select::make('service_point_id')
                        ->relationship('servicePoints', 'name')
                        ->label(__('filament.service_points.label'))
                        ->native(false)
                        ->multiple()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('identifier')
                        ->required()
                        ->maxLength(255)
                        ->label(__('filament.customer_bikes.identifier')),
                    Forms\Components\TextInput::make('brand')
                        ->required()
                        ->maxLength(255)
                        ->label(__('filament.customer_bikes.brand')),
                    Forms\Components\TextInput::make('model')
                        ->required()
                        ->maxLength(255)
                        ->label(__('filament.customer_bikes.model')),
                    Forms\Components\Select::make('type')
                        ->native(false)
                        ->options(BikeType::class)
                        ->required()
                        ->searchable()
                        ->label(__('filament.customer_bikes.type')),
                    Forms\Components\TextInput::make('color')
                        ->required()
                        ->maxLength(255)
                        ->label(__('filament.customer_bikes.color')),
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('asset-images')
                        ->imageEditor()
                        ->label(__('filament.customer_bikes.image'))
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('specifications')
                        ->label(__('filament.customer_bikes.specifications'))
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
                    ->label(__('filament.customer_bikes.image'))
                    ->defaultImageUrl(url('/images/logo.png'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('servicePoints.name')
                    ->label(__('filament.service_points.label'))
                    ->badge()
                    ->color('undefined'),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label(__('filament.customer_bikes.owner'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('identifier')
                    ->label(__('filament.customer_bikes.identifier'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label(__('filament.customer_bikes.brand'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->label(__('filament.customer_bikes.model'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.customer_bikes.type'))
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label(__('filament.customer_bikes.color'))
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
                    ->label(__('filament.customer_bikes.owner'))
                    ->relationship('owner', 'name', function (Builder $query) {
                        $query->where('role_id', UserRoles::Customer);
                    })
                    ->preload()
                    ->native(false)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('service_point_id')
                    ->label(__('filament.service_points.label'))
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
                        // Deleting the image from the server when CustomerBike $record gets deleted.
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
