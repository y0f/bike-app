<?php

namespace App\Filament\Mechanic\Resources;

use Filament\Forms;
use App\Models\Slot;
use Filament\Tables;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\QueryException;
use App\Filament\Mechanic\Resources\ScheduleResource\Pages;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationLabel = 'Monteursrooster';

    protected static ?string $title = 'Monteursrooster';

    protected static ?string $slug = 'Monteursrooster';

    protected static ?string $pluralModelLabel = 'Monteursrooster';

    protected static ?string $label = 'Monteursrooster';

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        $mechanic = Filament::auth()->user();

        return $form
            ->schema([
                Forms\Components\Section::make('Monteursrooster')
                    ->description('Uw planning voor de dag')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->prefixIconColor('primary')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection()
                            ->label('Datum')
                            ->required(),
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
                            ])
                    ])
                    ->icon('heroicon-o-calendar')
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
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    // NOTE: needs to be filtered
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
