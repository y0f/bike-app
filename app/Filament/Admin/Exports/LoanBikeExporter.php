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
                ->label(__('admin-export.ID')),
            ExportColumn::make('servicePoint.name')
                ->label(__('admin-export.Servicepunt Naam')),
            ExportColumn::make('identifier')
                ->label(__('admin-export.Identificatienummer')),
            ExportColumn::make('brand')
                ->label(__('admin-export.Merk')),
            ExportColumn::make('model')
                ->label(__('admin-export.Model')),
            // ExportColumn::make('type')
            //     ->label(__('admin-export.Type')),
            ExportColumn::make('image')
                ->label(__('admin-export.Afbeelding')),
            ExportColumn::make('color')
                ->label(__('admin-export.Kleur')),
            ExportColumn::make('specifications')
                ->label(__('admin-export.Specificaties')),
            // ExportColumn::make('status')
            //     ->label(__('admin-export.Status')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $successfulRows = $export->successful_rows;
        $failedRowsCount = $export->getFailedRowsCount();

        $successfulRowsText = $successfulRows === 1 ? __('admin-export.failed_row_singular', ['count' => $successfulRows]) : __('admin-export.failed_row_plural', ['count' => $successfulRows]);
        $failedRowsText = $failedRowsCount === 1 ? __('admin-export.failed_row_singular', ['count' => $failedRowsCount]) : __('admin-export.failed_row_plural', ['count' => $failedRowsCount]);

        $body = __('admin-export.export_completed', ['count' => number_format($successfulRows)]);

        if ($failedRowsCount > 0) {
            $body .= ' ' . $failedRowsText;
        }

        return $body;
    }
}
