<?php

namespace App\Filament\Imports;

use App\Models\LoanBike;
use Filament\Facades\Filament;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class LoanBikeImporter extends Importer
{
    protected static ?string $model = LoanBike::class;

    public static function getColumns(): array
    {
        $user = Filament::auth()->user();

        return [
            ImportColumn::make('servicePoint')
                ->requiredMapping()
                ->relationship()
                ->rules(['required', function ($attribute, $value, $fail) use ($user) {
                    // Check if the provided servicePoint is associated with the admin
                    if (!$user->servicePoints->contains('id', $value)) {
                        $fail("Het geselecteerde servicepunt is niet gekoppeld aan uw account. U kunt deze koppelen via uw gebruikersinstellingen.");
                    }
                }])
                ->example(1), // Example servicePoint value

            ImportColumn::make('identifier')
                ->requiredMapping()
                ->rules([
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($user) {
                        // Check if the identifier already exists for the user's service points
                        $existingRecord = LoanBike::where('identifier', $value)
                            ->whereIn('service_point_id', $user->servicePoints->pluck('id')->toArray())
                            ->first();

                        if ($existingRecord) {
                            $fail("Kenteken '$value' is al geregistreerd!");
                        }
                    },
                ])
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
        $importedRows = $import->successful_rows;
        $failedRowsCount = $import->getFailedRowsCount();

        $importedRowsText = $importedRows === 1 ? 'rij' : 'rijen';
        $failedRowsText = $failedRowsCount === 1 ? 'rij' : 'rijen';

        $body = "De import van uw middel(en) is voltooid. Er " . ($importedRows === 1 ? 'is' : 'zijn') . " " . number_format($importedRows) . " $importedRowsText geïmporteerd.";

        if ($failedRowsCount === 1) {
            $body .= " $failedRowsCount $failedRowsText kon niet worden geïmporteerd.";
        } elseif ($failedRowsCount > 1) {
            $body .= " $failedRowsCount $failedRowsText konden niet worden geïmporteerd.";
        }

        return $body;
    }
}
