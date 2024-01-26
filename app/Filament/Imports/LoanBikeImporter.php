<?php

namespace App\Filament\Imports;

use App\Models\LoanBike;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class LoanBikeImporter extends Importer
{
    protected static ?string $model = LoanBike::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('servicePoint')
                ->requiredMapping()
                ->relationship()
                ->rules(['required'])
                ->example(1), // Example servicePoint value

            ImportColumn::make('identifier')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('BIKE-001'), // Example identifier value

            ImportColumn::make('brand')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Batavus'), // Example brand value

            ImportColumn::make('model')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Altura E-Go'), // Example model value

            ImportColumn::make('color')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Geel'), // Example color value

            ImportColumn::make('specifications')
                ->rules(['max:65535'])
                ->example('Aluminum frame'), // Example specifications value
        ];
    }

    public function resolveRecord(): ?LoanBike
    {
        // return LoanBike::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new LoanBike();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'De import van uw leenfiets is voltooid en ' . number_format($import->successful_rows) . ' ' . str('rij')->plural($import->successful_rows) . ' geïmporteerd.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
