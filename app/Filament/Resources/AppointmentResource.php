<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\Slot;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\LoanBike;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use App\Enums\LoanBikeStatus;
use App\Enums\AppointmentStatus;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\Pages\CreateAppointment;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationLabel = 'Afspraken';

    protected static ?string $title = 'afspraken';

    protected static ?string $slug = 'afspraken';

    protected static ?string $pluralModelLabel = 'afspraken';

    protected static ?string $label = 'Afspraak';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Planning';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        $mechanic = Role::whereName('mechanic')->first();

        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('customer_bike_id')
                        ->label('Voertuig')
                        ->native(false)
                        ->relationship('customerBike', 'identifier')
                        ->required(),
                    Forms\Components\DatePicker::make('date')
                        ->label('Datum')
                        ->native(false)
                        ->live()
                        ->required()
                        ->afterStateUpdated(fn (Set $set) => $set('mechanic_id', null)),

                    // We only want to show the mechanics that are available on the day of selection
                    Forms\Components\Select::make('mechanic_id')
                        ->label('Monteur')
                        ->options(function (Get $get) use ($mechanic) {
                            return User::whereBelongsTo($mechanic)
                                ->whereHas('schedules', function (Builder $query) use ($get) {
                                    $query->where('date', $get('date'));
                                })
                                ->get()
                                ->pluck('name', 'id');
                        })
                        ->native(false)
                        ->hidden(fn (Get $get) => blank($get('date')))
                        ->live()
                        ->required()
                        ->afterStateUpdated(fn (Set $set) => $set('slot_id', null)),

                    // We only want slots from the selected mechanic
                    Forms\Components\Select::make('slot_id')
                        ->native(false)
                        ->relationship(
                            name: 'slot',
                            titleAttribute: 'start',
                            modifyQueryUsing: function (Builder $query, Get $get) {
                                $mechanicToRetrieveSlotsFrom = User::find($get('mechanic_id'));
                                $query->whereHas('schedule', function (Builder $query) use ($mechanicToRetrieveSlotsFrom) {
                                    $query->whereBelongsTo($mechanicToRetrieveSlotsFrom, 'owner');
                                });
                            }
                        )
                        ->hidden(fn (Get $get) => blank($get('mechanic_id')))
                        ->getOptionLabelFromRecordUsing(fn (Slot $record) => $record->start->format('H:i'))
                        ->live()
                        ->required(),

                    Forms\Components\Toggle::make('has_loan_bike')
                        ->label('Is er een leenmiddel van toepassing?')
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x-mark')
                        ->live(),

                    Forms\Components\Select::make('loan_bike_id')
                        ->label('Loan Bike')
                        ->label('Leenmiddel naar keuze')
                        ->options(
                            LoanBike::where('status', LoanBikeStatus::Available)->pluck('identifier', 'id')
                        )
                        ->native(false)
                        ->preload()
                        ->live()
                        ->visible(fn (Get $get) => $get('has_loan_bike') == true),

                    Forms\Components\Select::make('status')
                        ->options(AppointmentStatus::class)
                        ->required()
                        ->hidden(fn ($livewire) => $livewire instanceof CreateAppointment),
                    Forms\Components\RichEditor::make('description')
                        ->label('Omschrijving')
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hintColor('primary')
                        ->hintIconTooltip('Wat is het probleem met uw voertuig?')
                        ->required()
                        ->columnSpanFull(),
                ])
                    ->icon('heroicon-o-calendar-days')
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('customerBike.identifier')
                    ->label('Voertuig')
                    ->numeric()
                    ->searchable()
                    ->limit(12)
                    ->sortable(),
                Tables\Columns\TextColumn::make('slot.schedule.owner.name')
                    ->label('Monteur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slot.schedule.date')
                    ->label('Datum')
                    ->date('d-m-Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slot.formatted_time')
                    ->label('Tijdslot')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('loanBike.identifier')
                    ->placeholder('N.V.T.')
                    ->label('Leenmiddel'),
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
                Tables\Actions\Action::make('Voltooien')
                    ->action(function (Appointment $record) {
                        $record->status = AppointmentStatus::Completed;
                        $record->loan_bike_id = NULL;
                        $record->save();

                        // Update LoanBike status to available
                        if ($record->loanBike) {
                            $record->loanBike->status = LoanBikeStatus::Available;
                            $record->loanBike->save();
                        }
                    })
                    ->visible(fn (Appointment $record)
                    => $record->status !== AppointmentStatus::Completed
                        && $record->status !== AppointmentStatus::Cancelled)
                    ->color('success')
                    ->icon('heroicon-o-check'),

                Tables\Actions\Action::make('Annuleren')
                    ->action(function (Appointment $record) {
                        $record->status = AppointmentStatus::Cancelled;
                        $record->loan_bike_id = NULL;
                        $record->save();

                        // Update LoanBike status to available
                        if ($record->loanBike) {
                            $record->loanBike->status = LoanBikeStatus::Available;
                            $record->loanBike->save();
                        }
                    })
                    ->visible(fn (Appointment $record)
                    => $record->status !== AppointmentStatus::Cancelled
                        && $record->status !== AppointmentStatus::Completed)
                    ->color('danger')
                    ->icon('heroicon-o-x-mark'),

                Tables\Actions\EditAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('description')
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view'   => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
