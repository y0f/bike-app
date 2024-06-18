<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\Slot;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\DaysOfTheWeek;
use App\Models\ServicePoint;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use App\Filament\Resources\ScheduleResource\Pages;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationLabel = 'Monteur roosters';

    protected static ?string $title = 'Monteur roosters';

    protected static ?string $slug = 'monteur-roosters';

    protected static ?string $pluralModelLabel = 'Monteur roosters';

    protected static ?string $label = 'Monteur rooster';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $mechanic = Role::whereName('mechanic')->first();

        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.mechanic_schedule'))
                    ->description(__('filament.mechanic_schedule_description'))
                    ->schema([
                        Forms\Components\Select::make('service_point_id')
                            ->prefixIcon('icon-service-point')
                            ->prefixIconColor('primary')
                            ->relationship('servicePoint', 'name')
                            ->label(__('filament.service_points'))
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('owner_id', null)),
                        Forms\Components\Select::make('owner_id')
                            ->prefixIcon('heroicon-o-cog')
                            ->prefixIconColor('primary')
                            ->native(false)
                            ->label(__('filament.mechanic'))
                            ->options(function (Get $get) use ($mechanic): array|Collection {
                                return ServicePoint::find($get('service_point_id'))
                                    ?->users()
                                    ->whereBelongsTo($mechanic)
                                    ->get()
                                    ->pluck('name', 'id') ?? [];
                            })
                            ->required()
                            ->live(),
                        Forms\Components\Select::make('day_of_the_week')
                            ->prefixIcon('icon-day')
                            ->prefixIconColor('primary')
                            ->label(__('filament.day_of_the_week'))
                            ->options(DaysOfTheWeek::class)
                            ->native(false)
                            ->required(),
                        Forms\Components\Repeater::make('slots')
                            ->label(__('filament.time_slots'))
                            ->relationship()
                            ->schema([
                                Forms\Components\TimePicker::make('start')
                                    ->prefixIcon('heroicon-o-paper-airplane')
                                    ->prefixIconColor('primary')
                                    ->seconds(false)
                                    ->required(),
                                Forms\Components\TimePicker::make('end')
                                    ->prefixIcon('heroicon-o-paper-airplane')
                                    ->prefixIconColor('primary')
                                    ->seconds(false)
                                    ->required(),
                            ])->columnSpanFull()
                    ])
                    ->icon('heroicon-o-clock')
                    ->iconColor('primary')
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup(
                Tables\Grouping\Group::make('servicePoint.name')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false)
            )
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label(__('filament.mechanic'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('slots')
                    ->label(__('filament.slots'))
                    ->badge()
                    ->formatStateUsing(fn (Slot $state) => $state->start->format('H:i') . ' - ' . $state->end->format('H:i')),
                Tables\Columns\TextColumn::make('day_of_the_week')
                    ->label(__('filament.workday'))
                    ->badge(),
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
                    ->before(fn (Schedule $record) => $record->slots()->delete()),
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
