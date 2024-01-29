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

    protected static ?string $heading = 'Nieuwste afspraken';

    public function table(Table $table): Table
    {
        return $table
            ->query(AppointmentResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
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
                ->badge(),
            Tables\Columns\TextColumn::make('loanBike.identifier')
                ->placeholder(new HtmlString(view('heroicons.false')->render()))
                ->label('Leenmiddel'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Aangemaakt op')
                ->dateTime()
                ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->url(fn (Appointment $record): string => AppointmentResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}