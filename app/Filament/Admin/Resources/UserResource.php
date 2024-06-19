<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Enums\UserRoles;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\Pages\ViewUser;
use Filament\Forms\Components\ToggleButtons;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('filament.phone'))
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('filament.email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label(__('filament.password'))
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->hidden(fn ($livewire) => $livewire instanceof ViewUser)
                            ->maxLength(255),
                    ])
                        ->description(__('filament.basic_info'))
                        ->icon('heroicon-o-users')
                        ->columns(2),
                    Forms\Components\Section::make([
                        Forms\Components\Select::make('service_point_id')
                            ->relationship('servicePoints', 'name')
                            ->label(__('filament.service_points.plural_label'))
                            ->native(false)
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->hintIcon('heroicon-o-question-mark-circle')
                            ->hintColor('primary')
                            ->hintIconTooltip(__('filament.service_points_hint')),
                    ])
                        ->description(__('filament.branches'))
                        ->icon('heroicon-o-map-pin')
                ]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make([
                        ToggleButtons::make('role_id')
                            ->label(__('filament.role'))
                            ->inline()
                            ->options(UserRoles::class)
                            ->colors([
                                UserRoles::Admin->getIcon(),
                                UserRoles::Staff->getIcon(),
                                UserRoles::Mechanic->getIcon(),
                                UserRoles::Customer->getIcon(),
                            ])
                            ->icons([
                                UserRoles::Admin->getColor(),
                                UserRoles::Staff->getColor(),
                                UserRoles::Mechanic->getColor(),
                                UserRoles::Customer->getColor(),
                            ])
                            ->required()
                            ->extraAttributes(['class' => 'p-2']),
                    ])
                        ->columns(1)
                        ->description(__('filament.user_rights'))
                        ->icon('heroicon-o-user-circle'),
                    Forms\Components\Section::make([
                        Forms\Components\FileUpload::make('avatar_url')
                            ->label(__('filament.avatar'))
                            ->image()
                            ->imageEditor()
                            ->imageEditorMode(2),
                    ])
                        ->columns(1)
                        ->description(__('filament.profile_photo'))
                        ->icon('heroicon-o-camera'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label(__('filament.avatar'))
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('filament.phone'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament.email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role_id')
                    ->label(__('filament.role'))
                    ->badge()
                    ->sortable()
                    ->formatStateUsing(function (string $state): string {
                        $role = UserRoles::from($state);

                        return $role->getLabel() ?? $state;
                    }),
                Tables\Columns\TextColumn::make('servicePoints.name')
                    ->label(__('filament.service_points.plural_label'))
                    ->badge()
                    ->color('undefined'),
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

    public static function getLabel(): string
    {
        return __('filament.users.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.users.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.users.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.users.navigation_group');
    }

    public static function getTitle(): string
    {
        return __('filament.users.title');
    }

    public static function getSlug(): string
    {
        return __('filament.user.slug');
    }
}
