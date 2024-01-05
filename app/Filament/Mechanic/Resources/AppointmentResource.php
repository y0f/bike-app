<?php

namespace App\Filament\Mechanic\Resources;

use Filament\Forms;
use App\Models\Slot;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\LoanBike;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use App\Enums\LoanBikeStatus;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use App\Enums\AppointmentStatus;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use App\Filament\Mechanic\Resources\AppointmentResource\Pages;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationLabel = 'Afspraken';

    protected static ?string $title = 'afspraken';

    protected static ?string $slug = 'afspraken';

    protected static ?string $pluralModelLabel = 'afspraken';

    protected static ?string $label = 'Afspraak';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        /** @var \App\Models\ServicePoint $servicePoint the auth user's servicePoint */
        $servicePoint = Filament::getTenant();

        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('customer_bike_id')
                        ->label('Voertuig')
                        ->native(false)
                        ->relationship('customerBike', 'identifier')
                        ->required()
                        ->preload()
                        /** Retrieve helper text based on the availability of customer bikes in the current service point. */
                        ->helperText(fn () => $servicePoint->customerBikes->isEmpty() ?
                            new HtmlString(
                                '<span class="text-sm text-danger-600 dark:text-danger-400">Er zijn geen geregistreerde tweewielers van klanten in dit servicepunt.</span>'
                            ) : ''),

                    Forms\Components\DatePicker::make('date')
                        ->label('Datum')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->closeOnDateSelection()
                        ->live()
                        ->required()
                        ->afterStateUpdated(function (Set $set) {
                            $set('slot_id', null);
                        }),

                    // We only want slots from the logged in mechanic's tenant that don't have an appointment on the date given.
                    Forms\Components\Select::make('slot_id')
                        ->label('Tijdslot')
                        ->native(false)
                        ->options(function (Get $get) use ($servicePoint) {
                            /** @var \App\Models\User $mechanic */
                            $mechanic = Filament::auth()->user();
                            /** @var \Illuminate\Support\Carbon $date */
                            $date = Carbon::parse($get('date'));

                            return Slot::availableFor($mechanic, $date->dayOfWeek, $servicePoint->id, $date)
                                ->get()
                                ->pluck('formatted_time', 'id');
                        })
                        ->hidden(fn (Get $get) => blank($get('date')))
                        ->live()
                        ->helperText(function ($component) {
                            if (!$component->getOptions()) {
                                return new HtmlString(
                                    '<span class="text-sm text-danger-600 dark:text-danger-400">Geen beschikbare tijdsloten. Selecteer alstublieft een andere datum.</span>'
                                );
                            }

                            return '';
                        })
                        ->required(),

                    Forms\Components\Toggle::make('has_loan_bike')
                        ->label('Is er een leenmiddel van toepassing?')
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x-mark')
                        ->live()
                        ->columnSpanFull()
                        ->afterStateUpdated(function (Set $set) {
                            $set('loan_bike_id', null);
                        }),

                    Forms\Components\Select::make('loan_bike_id')
                        ->label('Leenmiddel naar keuze')
                        ->relationship('loanBike', 'identifier')
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
                        ->native(false)
                        ->visibleOn(Pages\EditAppointment::class),

                    Forms\Components\RichEditor::make('description')
                        ->label('Omschrijving')
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hintColor('primary')
                        ->hintIconTooltip('Wat is het probleem met uw voertuig?')
                        ->required()
                        ->columnSpanFull(),
                ])
                    ->icon('heroicon-o-calendar-days')
                    ->columns(3)
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
                    ->limit(16)
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Datum')
                    ->sortable()
                    ->date('d-m-y')
                    ->searchable(),
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
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('Voltooien')
                    ->action(function (Appointment $record) {
                        $record->status = AppointmentStatus::Completed;
                        $record->has_loan_bike = false;
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
                        $record->has_loan_bike = false;
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
            AppointmentResource\RelationManagers\NotesRelationManager::class,
            AppointmentResource\RelationManagers\LogsRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::new()->count();
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
