<?php

namespace App\Filament\Exports;

use App\Models\LoanBike;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LoanBikeExporter extends Exporter
{
    protected static ?string $model = LoanBike::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('servicePoint.name')
                ->label('Servicepunt Naam'),
            ExportColumn::make('identifier')
                ->label('Identificatienummer'),
            ExportColumn::make('brand')
                ->label('Merk'),
            ExportColumn::make('model')
                ->label('Model'),
            // ExportColumn::make('type')
            //     ->label('Type'),
            ExportColumn::make('image')
                ->label('Afbeelding'),
            ExportColumn::make('color')
                ->label('Kleur'),
            ExportColumn::make('specifications')
                ->label('Specificaties'),
            // ExportColumn::make('status')
            //     ->label('Status'),
        ];
    }


    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Uw fietsuitleen export is voltooid en ' . number_format($export->successful_rows) . ' ' . str('rij')->plural($export->successful_rows) . ' geëxporteerd.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('rij')->plural($failedRowsCount) . ' konden niet geëxporteerd worden.';
        }

        return $body;
    }

}
