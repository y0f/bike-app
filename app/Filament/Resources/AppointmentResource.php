<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\Slot;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Enums\UserRoles;
use App\Models\LoanBike;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use App\Models\CustomerBike;
use App\Models\ServicePoint;
use App\Enums\LoanBikeStatus;
use Illuminate\Support\Carbon;
use App\Enums\AppointmentStatus;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\Pages\CreateAppointment;
use App\Filament\Resources\AppointmentResource\RelationManagers\NotesRelationManager;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationLabel = 'Afspraken';

    protected static ?string $title = 'afspraken';

    protected static ?string $slug = 'afspraken';

    protected static ?string $pluralModelLabel = 'afspraken';

    protected static ?string $label = 'Afspraak';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $recordTitleAttribute = 'id';

    protected static int $globalSearchResultsLimit = 20;

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
                            $set('customer_bike_id', null);
                        }),

                    Forms\Components\Select::make('customer_bike_id')
                        ->label('Voertuig')
                        ->native(false)
                        ->options(function (Get $get) {
                            $servicePointId = $get('service_point_id');

                            // Fetch customer bikes belonging to the selected service point
                            $customerBikes = CustomerBike::whereHas('servicePoints', function ($query) use ($servicePointId) {
                                $query->where('service_point_id', $servicePointId);
                            })->pluck('identifier', 'id');

                            return $customerBikes;
                        })
                        ->required()
                        ->helperText(function ($component) {
                            if (!$component->getOptions()) {
                                return new HtmlString(
                                    '<span class="text-sm text-danger-600 dark:text-danger-400">Geen beschikbare middelen.</span>'
                                );
                            }

                            return '';
                        }),

                    Forms\Components\DatePicker::make('date')
                        ->label('Datum')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->closeOnDateSelection()
                        ->live()
                        ->required()
                        ->hidden(fn (Get $get) => blank($get('service_point_id')))
                        ->afterStateUpdated(function (Set $set) {
                            $set('mechanic_id', null);
                            $set('slot_id', null);
                        }),

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
                        ->label('Tijdslot')
                        ->native(false)
                        ->options(function (Get $get) {
                            $mechanicId = $get('mechanic_id');
                            $mechanic = User::find($mechanicId);

                            $servicePointId = $get('service_point_id');
                            $servicePoint = ServicePoint::find($servicePointId);

                            $date = Carbon::parse($get('date'));

                            return $servicePoint ? Slot::availableFor($mechanic, $date->dayOfWeek, $servicePoint->id, $date)
                                ->get()
                                ->pluck('formatted_time', 'id') : [];
                        })
                        ->hidden(fn (Get $get) => blank($get('mechanic_id')))
                        ->getOptionLabelFromRecordUsing(fn (Slot $record) => $record->start->format('H:i'))
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
                        ->afterStateUpdated(function (Set $set) {
                            $set('loan_bike_id', null);
                        })
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
                        ->visible(fn (Get $get) => $get('has_loan_bike') == true)
                        ->hidden(fn (Get $get) => blank($get('service_point_id'))),

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
            ->defaultGroup(
                Tables\Grouping\Group::make('servicePoint.name')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false)
            )
            ->columns([
                // ID visually here so there's a reference for the admin in 'Activiteitenlogboek'.
                // We hide it by default but it is searchable.
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('loanBike.identifier')
                    ->placeholder(new HtmlString(view('heroicons.false')->render()))
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
                Tables\Filters\SelectFilter::make('name')
                    ->label('Monteur')
                    ->relationship('mechanic', 'name', function (Builder $query) {
                        $query->where('role_id', UserRoles::Mechanic);
                    })
                    ->preload()
                    ->native(false)
                    ->searchable(),
                Tables\Filters\SelectFilter::make('service_point_id')
                    ->label('Servicepunt')
                    ->multiple()
                    ->preload()
                    ->native(false)
                    ->relationship('servicePoint', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(AppointmentStatus::class)
                    ->preload()
                    ->native(false)
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        // Eager loading 'mechanic', 'status', and 'servicePoint' relationships
        return parent::getGlobalSearchEloquentQuery()->with(['mechanic', 'servicePoint']);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        try {
            // Ensuring the relationships are loaded
            $record->load('mechanic', 'servicePoint');

            $date = $record->date ?? 'N/A';
            $dateString = $date->toDateString('D-M-Y');
            $status = $record->status ?? 'N/A';
            $statusLabel = $status->getLabel();

            /** @var string $mechanicName*/
            $mechanicName = $record->mechanic->name ?? 'N/A';
            /** @var string $servicePointName*/
            $servicePointName = $record->servicePoint->name ?? 'N/A';

            return [
                'Monteur'      => $mechanicName,
                'Datum'          => $dateString,
                'Status'        => $statusLabel,
                'Servicepunt' => $servicePointName,
            ];
        } catch (\Exception $exception) {
            return [
                'error' => "Er is een fout opgetreden. :(",
            ];
        }
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['date', 'mechanic.name'];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                //  TextEntry::make('description')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            NotesRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
