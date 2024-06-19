<?php

namespace App\Filament\Admin\Exports;

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
        $successfulRows = $export->successful_rows;
        $failedRowsCount = $export->getFailedRowsCount();

        $successfulRowsText = $successfulRows === 1 ? 'rij' : 'rijen';
        $failedRowsText = $failedRowsCount === 1 ? 'rij' : 'rijen';

        $body = "Uw leenmiddelen export is voltooid en " . number_format($successfulRows) . " $successfulRowsText geëxporteerd.";

        if ($failedRowsCount === 1) {
            $body .= " $failedRowsCount $failedRowsText kon niet worden geëxporteerd.";
        } elseif ($failedRowsCount > 1) {
            $body .= " " . number_format($failedRowsCount) . " $failedRowsText konden niet worden geëxporteerd.";
        }

        return $body;
    }

}
