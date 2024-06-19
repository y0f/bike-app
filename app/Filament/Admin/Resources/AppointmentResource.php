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
                        ->label(__('filament.customer_bike_id'))
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
                                    '<span class="text-sm text-danger-600 dark:text-danger-400">' . __('filament.no_resources_available') . '</span>'
                                );
                            }

                            return '';
                        }),

                    Forms\Components\DatePicker::make('date')
                        ->label(__('filament.date'))
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
                        ->label(__('filament.mechanic_id'))
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
                                    "<span class='text-sm text-danger-600 dark:text-primary-400'>" . __('filament.no_mechanics_available') . "</span>"
                                );
                            }

                            return '';
                        }),

                    Forms\Components\Select::make('slot_id')
                        ->label(__('filament.slot_id'))
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
                                    '<span class="text-sm text-danger-600 dark:text-danger-400">' . __('filament.no_slots_available') . '</span>'
                                );
                            }

                            return '';
                        })
                        ->required(),

                    Forms\Components\Toggle::make('has_loan_bike')
                        ->label(__('filament.has_loan_bike'))
                        ->onIcon('heroicon-o-check')
                        ->offIcon('heroicon-o-x-mark')
                        ->live()
                        ->afterStateUpdated(function (Set $set) {
                            $set('loan_bike_id', null);
                        })
                        ->hidden(fn (Get $get) => blank($get('service_point_id')))
                        ->columnSpanFull(),

                    Forms\Components\Select::make('loan_bike_id')
                        ->label(__('filament.loan_bike_id'))
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
                        ->label(__('filament.description'))
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hintColor('primary')
                        ->hintIconTooltip(__('filament.description_hint'))
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
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('filament.id')),

                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->label(__('filament.status')),

                Tables\Columns\TextColumn::make('customerBike.identifier')
                    ->label(__('filament.customerBike.identifier'))
                    ->limit(12),

                Tables\Columns\TextColumn::make('mechanic.name')
                    ->label(__('filament.mechanic')),

                Tables\Columns\TextColumn::make('date')
                    ->label(__('filament.date'))
                    ->sortable()
                    ->date('d-m-y')
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('slot.formatted_time')
                    ->label(__('filament.slot.formatted_time'))
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('loanBike.identifier')
                    ->placeholder(new HtmlString(view('heroicons.false')->render()))
                    ->label(__('filament.loanBike.identifier')),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('filament.created_at')),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('filament.updated_at')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('name')
                    ->label(__('filament.mechanic'))
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
                    ->label(__('filament.status'))
                    ->options(AppointmentStatus::class)
                    ->preload()
                    ->native(false)
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),

                    Tables\Actions\Action::make(__('filament.complete'))
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

                    Tables\Actions\Action::make(__('filament.cancel'))
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

                    Tables\Actions\EditAction::make()->color('warning'),
                ])
                ->button()
                ->color('gray')
                ->label('acties')
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getGlobalSearchEloquentQuery(): Builder
    {
        // Eager loading 'mechanic' and 'servicePoint' relationships
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
                'Datum'        => $dateString,
                'Status'       => $statusLabel,
                'Servicepunt'  => $servicePointName,
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
