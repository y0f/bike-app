<?php

namespace App\Filament\Mechanic\Resources;

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
use Illuminate\Support\Carbon;
use App\Enums\AppointmentStatus;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Mechanic\Resources\AppointmentResource\Pages;
use App\Filament\Mechanic\Resources\AppointmentResource\RelationManagers;
use App\Filament\Mechanic\Resources\AppointmentResource\Pages\CreateAppointment;

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
                    Forms\Components\Select::make('service_point_id')
                        ->relationship('servicePoint', 'name')
                        ->label('Servicepunten')
                        ->native(false)
                        ->preload()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('date', null);
                            $set('mechanic_id', null);
                            $set('has_loan_bike', null);
                            $set('loan_bike_id', null);
                        }),

                    Forms\Components\Select::make('customer_bike_id')
                        ->label('Voertuig')
                        ->native(false)
                        ->relationship('customerBike', 'identifier')
                        ->required(),

                    Forms\Components\DatePicker::make('date')
                        ->label('Datum')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->closeOnDateSelection()
                        ->live()
                        ->required()
                        ->hidden(fn (Get $get) => blank($get('service_point_id')))
                        ->afterStateUpdated(fn (Set $set) => $set('mechanic_id', null)),

                    // We only want to show the mechanics that are available on the day of selection and within the selected service point.
                    Forms\Components\Select::make('mechanic_id')
                        ->label('Monteur')
                        ->options(function (Get $get) use ($mechanic) {
                            return User::whereBelongsTo($mechanic)
                                ->whereHas('schedules', function (Builder $query) use ($get) {
                                    $dayOfTheWeek = Carbon::parse($get('date'))->dayOfWeek;
                                    $query
                                        ->where('day_of_the_week', $dayOfTheWeek)
                                        ->where('service_point_id', $get('service_point_id'));
                                })
                                ->pluck('name', 'id');
                        })
                        ->native(false)
                        ->hidden(fn (Get $get) => blank($get('date')))
                        ->live()
                        ->required()
                        ->afterStateUpdated(fn (Set $set) => $set('slot_id', null))
                        ->helperText(function ($component) {
                            if (!$component->getOptions()) {
                                return new HtmlString(
                                    "<span class='text-sm text-danger-600 dark:text-primary-400'>Geen monteurs beschikbaar, selecteer een andere datum of servicepunt. :(</span>"
                                );
                            }

                            return '';
                        }),

                    // We only want slots from the selected mechanic within the selected servicepoint.
                    Forms\Components\Select::make('slot_id')
                        ->native(false)
                        ->options(function (Get $get) {
                            $mechanic = User::find($get('mechanic_id'));
                            $dayOfTheWeek = Carbon::parse($get('date'))->dayOfWeek;
                            $servicePointId = $get('service_point_id');

                            return $servicePointId ? Slot::availableFor($mechanic, $dayOfTheWeek, $servicePointId)->get()->pluck('formatted_time', 'id') : [];
                        })
                        ->hidden(fn (Get $get) => blank($get('mechanic_id')))
                        ->getOptionLabelFromRecordUsing(fn (Slot $record) => $record->start->format('H:i'))
                        ->live()
                        ->required(),

                    Forms\Components\Toggle::make('has_loan_bike')
                        ->label('Is er een leenmiddel van toepassing?')
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x-mark')
                        ->live()
                        ->hidden(fn (Get $get) => blank($get('service_point_id')))
                        ->columnSpanFull(),

                    Forms\Components\Select::make('loan_bike_id')
                        ->label('Leenmiddel naar keuze')
                        ->relationship('loanBike', 'identifier')
                        ->options(
                            LoanBike::where('status', LoanBikeStatus::Available)->pluck('identifier', 'id')
                        )
                        ->native(false)
                        ->preload()
                        ->live()
                        ->hidden(fn (Get $get) => blank($get('service_point_id')))
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
                    ->sortable()
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('customerBike.identifier')
                    ->label('Voertuig')
                    ->numeric()
                    ->searchable()
                    ->limit(12)
                    ->sortable(),

                // I'm leaving this here for testing purposes for now, needs to be removed eventually.
                Tables\Columns\TextColumn::make('mechanic.name')
                    ->label('Monteur')
                    ->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->sortable()
                    ->date('d-m-y')
                    ->searchable()
                    ->badge(),
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
                        $record->loan_bike_id = null;
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
                        $record->loan_bike_id = null;
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
