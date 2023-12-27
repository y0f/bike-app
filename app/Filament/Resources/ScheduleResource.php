<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\Slot;
use App\Models\User;
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
use Illuminate\Database\QueryException;
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

    protected static ?string $navigationGroup = 'Planning';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $mechanic = Role::whereName('mechanic')->first();

        return $form
            ->schema([
                Forms\Components\Section::make('Monteursrooster')
                    ->description('De tijdsloten van de monteur per dag.')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->prefixIconColor('primary')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection()
                            ->label('Datum')
                            ->required(),
                        Forms\Components\Select::make('service_point_id')
                            ->relationship('servicePoint', 'name')
                            ->label('Servicepunten')
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('owner_id', null)),
                        Forms\Components\Select::make('owner_id')
                            ->prefixIcon('heroicon-o-cog')
                            ->prefixIconColor('primary')
                            ->native(false)
                            ->label('Monteur')
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
                            ->label('Dag van de week')
                            ->options(DaysOfTheWeek::class)
                            ->native(false),
                        Forms\Components\Repeater::make('slots')
                            ->label('Tijdvakken')
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
            ->groups([
                Tables\Grouping\Group::make('date')
                    ->label('Datum')
                    ->collapsible()
            ])
            ->defaultGroup('date')
            ->defaultSort('created_at', 'desc')
            // ->groupingSettingsInDropdownOnDesktop()
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('servicePoint.name')
                    ->label('Servicepunt')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Monteur')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slots')
                    ->label('Tijdvakken')
                    ->badge()
                    ->formatStateUsing(fn (Slot $state) => $state->start->format('H:i') . ' - ' . $state->end->format('H:i')),
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
        try {
            return static::getModel()::count();
        } catch (QueryException $e) {
            return 0;
        }
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
