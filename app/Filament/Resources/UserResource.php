<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Enums\UserRoles;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\ViewUser;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Gebruikers';

    protected static ?string $title = 'gebruikers';

    protected static ?string $slug = 'gebruikers';

    protected static ?string $pluralModelLabel = 'gebruikers';

    protected static ?string $label = 'gebruiker';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Voor & achternaam')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('phone')
                        ->label('Telefoonnummer')
                        ->tel()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('service_point_id')
                        ->relationship('servicePoints', 'name')
                        ->label('Servicepunten')
                        ->native(false)
                        ->multiple()
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('password')
                        ->label('Wachtwoord')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create')
                        ->hidden(fn ($livewire) => $livewire instanceof ViewUser)
                        ->maxLength(255),
                    Forms\Components\Select::make('role_id')
                        ->label('Gebruikersrol')
                        ->preload()
                        ->native(false)
                        ->options(UserRoles::class)
                        ->required(),
                    Forms\Components\FileUpload::make('avatar_url')
                        ->label('Profielfoto')
                        ->image(),
                ])
                    ->icon('heroicon-o-users')
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Profielfoto')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefoonnummer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role_id')
                    ->label('Rol')
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(function (string $state): string {
                        $role = UserRoles::from($state);

                        return $role->getLabel() ?? $state;
                    }),
                Tables\Columns\TextColumn::make('servicePoints.name')
                    ->label('Servicepunten')
                    ->badge()
                    ->color('undefined'),
                // Note: need to fix bugs in this.
                // ->sortable()
                //->searchable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    // NOTE: These cause duplicated queries.
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // NOTE: This still allows users to guess the url, if no policies are set.
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->role->name == 'admin';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
