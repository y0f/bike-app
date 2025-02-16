<?php

namespace App\Filament\Admin\Resources;

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
use App\Filament\Admin\Resources\AppointmentResource\Pages;
use App\Filament\Admin\Resources\AppointmentResource\Pages\CreateAppointment;
use App\Filament\Admin\Resources\AppointmentResource\Actions\CancelAppointmentAction;
use App\Filament\Admin\Resources\AppointmentResource\Actions\CompleteAppointmentAction;
use App\Filament\Admin\Resources\AppointmentResource\RelationManagers\NotesRelationManager;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static int $globalSearchResultsLimit = 20;

    public static function form(Form $form): Form
    {
        $mechanic = Role::whereName('mechanic')->first();

        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Select::make('service_point_id')
                        ->relationship('servicePoint', 'name')
                        ->label(__('filament.service_points.label'))
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
                        ->label(__('filament.appointments.customer_bike_id'))
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
                                    '<span class="text-sm text-danger-600 dark:text-danger-400">' . __('filament.appointments.no_resources_available') . '</span>'
                                );
                            }

                            return '';
                        }),

                    Forms\Components\DatePicker::make('date')
                        ->label(__('filament.appointments.date'))
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

                    Forms\Components\Select::make('mechanic_id')
                        ->label(__('filament.appointments.mechanic_id'))
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
                                    "<span class='text-sm text-danger-600 dark:text-primary-400'>" . __('filament.appointments.no_mechanics_available') . "</span>"
                                );
                            }

                            return '';
                        }),

                    Forms\Components\Select::make('slot_id')
                        ->label(__('filament.appointments.slot_id'))
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
                                    '<span class="text-sm text-danger-600 dark:text-danger-400">' . __('filament.appointments.no_slots_available') . '</span>'
                                );
                            }

                            return '';
                        })
                        ->required(),

                    Forms\Components\Toggle::make('has_loan_bike')
                        ->label(__('filament.appointments.has_loan_bike'))
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x-mark')
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('loan_bike_id', null);
                        })
                        ->hidden(fn (Get $get) => blank($get('service_point_id')))
                        ->columnSpanFull(),

                    Forms\Components\Select::make('loan_bike_id')
                        ->label(__('filament.appointments.loan_bike_id'))
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
                        ->label(__('filament.appointments.status'))
                        ->options(AppointmentStatus::class)
                        ->required()
                        ->hidden(fn ($livewire) => $livewire instanceof CreateAppointment),

                    Forms\Components\RichEditor::make('description')
                        ->label(__('filament.appointments.description'))
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hintColor('primary')
                        ->hintIconTooltip(__('filament.appointments.description_hint'))
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
                // ID visually here so there's a reference for the admin in 'Activity Log'.
                // We hide it by default but it is searchable.
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('filament.appointments.id')),

                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->label(__('filament.appointments.status')),

                Tables\Columns\TextColumn::make('customerBike.identifier')
                    ->label(__('filament.appointments.customer_bike_identifier'))
                    ->limit(12),

                Tables\Columns\TextColumn::make('mechanic.name')
                    ->label(__('filament.appointments.mechanic')),

                Tables\Columns\TextColumn::make('date')
                    ->label(__('filament.appointments.date'))
                    ->sortable()
                    ->date('d-m-y')
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('slot.formatted_time')
                    ->label(__('filament.appointments.slot_formatted_time'))
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('loanBike.identifier')
                    ->placeholder(new HtmlString(view('heroicons.false')->render()))
                    ->label(__('filament.appointments.loan_bike_identifier')),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('filament.appointments.created_at')),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('filament.appointments.updated_at')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('name')
                    ->label(__('filament.appointments.mechanic'))
                    ->relationship('mechanic', 'name', function (Builder $query) {
                        $query->where('role_id', UserRoles::Mechanic);
                    })
                    ->preload()
                    ->native(false)
                    ->searchable(),

                Tables\Filters\SelectFilter::make('service_point_id')
                    ->label(__('filament.service_points.label'))
                    ->multiple()
                    ->preload()
                    ->native(false)
                    ->relationship('servicePoint', 'name')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filament.appointments.status'))
                    ->options(AppointmentStatus::class)
                    ->preload()
                    ->native(false)
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    CompleteAppointmentAction::make(__('filament.complete')),
                    CancelAppointmentAction::make(__('filament.cancel')),
                    Tables\Actions\EditAction::make()->color('warning'),
                ])
                ->button()
                ->color('gray')
                ->label('Actions')
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['mechanic', 'servicePoint']);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        if (!$record instanceof Appointment) {
            return [
                'error' => 'Invalid record type.',
            ];
        }

        try {
            $record->loadMissing(['mechanic', 'servicePoint']);

            $date = $record->date ? $record->date->format('D-M-Y') : 'N/A';

            $statusLabel = $record->status instanceof AppointmentStatus
                ? $record->status->getLabel()
                : 'N/A';

            return [
                'Mechanic'       => $record->mechanic->name ?? 'N/A',
                'Date'           => $date,
                'Status'         => $statusLabel,
                'Service Point'  => $record->servicePoint->name ?? 'N/A',
            ];
        } catch (\Exception $exception) {
            return [
                'error' => 'An error occurred while fetching details.',
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

    public static function getLabel(): string
    {
        return __('filament.appointments.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament.appointments.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.appointments.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament.appointments.navigation_group');
    }

    public static function getTitle(): string
    {
        return __('filament.appointments.title');
    }

    public static function getSlug(): string
    {
        return __('filament.appointments.slug');
    }
}
