<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Appointment;
use Illuminate\Support\HtmlString;
use App\Filament\Resources\AppointmentResource;
use Filament\Widgets\TableWidget as BaseWidget;

class NewestAppointments extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return __('filament.newest_appointments_this_year');
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(AppointmentResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label(__('filament.id'))
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('status')
                ->label(__('filament.status'))
                ->sortable()
                ->searchable()
                ->badge(),
            Tables\Columns\TextColumn::make('customerBike.identifier')
                ->label(__('filament.customerBike.identifier'))
                ->numeric()
                ->searchable()
                ->limit(12)
                ->sortable(),
            Tables\Columns\TextColumn::make('mechanic.name')
                ->label(__('filament.mechanic'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('date')
               ->label(__('filament.date'))
                ->sortable()
                ->date('d-m-y')
                ->searchable()
                ->badge(),
            Tables\Columns\TextColumn::make('slot.formatted_time')
                ->label(__('filament.formatted_time'))
                ->badge(),
            Tables\Columns\TextColumn::make('loanBike.identifier')
                ->placeholder(new HtmlString(view('heroicons.false')->render()))
                ->label(__('filament.loan_bike')),
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('filament.created_at'))
                ->dateTime()
                ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('filament.view'))
                    ->url(fn (Appointment $record): string => AppointmentResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
