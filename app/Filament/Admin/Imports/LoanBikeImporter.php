<?php

namespace App\Filament\Admin\Imports;

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
                ->label(__('admin-import.servicePoint'))
                ->rules(['required', function ($attribute, $value, $fail) use ($user) {
                    if (!$user->servicePoints->contains('id', $value)) {
                        $fail(__('admin-import.servicePoint_error'));
                    }
                }])
                ->example(1),

            ImportColumn::make('identifier')
                ->requiredMapping()
                ->label(__('admin-import.identifier'))
                ->rules([
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($user) {
                        $existingRecord = LoanBike::where('identifier', $value)
                            ->whereIn('service_point_id', $user->servicePoints->pluck('id')->toArray())
                            ->first();

                        if ($existingRecord) {
                            $fail(__('admin-import.identifier_error', ['value' => $value]));
                        }
                    },
                ])
                ->example('BIKE-001'),

            ImportColumn::make('brand')
                ->requiredMapping()
                ->label(__('admin-import.brand'))
                ->rules(['required', 'max:255'])
                ->example('Batavus'),

            ImportColumn::make('model')
                ->requiredMapping()
                ->label(__('admin-import.model'))
                ->rules(['required', 'max:255'])
                ->example('Altura E-Go'),

            ImportColumn::make('color')
                ->requiredMapping()
                ->label(__('admin-import.color'))
                ->rules(['required', 'max:255'])
                ->example('Geel'),

            ImportColumn::make('specifications')
                ->label(__('admin-import.specifications'))
                ->rules(['max:65535'])
                ->example('Aluminum frame'),
        ];
    }

    public function resolveRecord(): ?LoanBike
    {
        return new LoanBike();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $importedRows = $import->successful_rows;
        $failedRowsCount = $import->getFailedRowsCount();

        $body = __('admin-import.import_completed', [
            'is_are' => $importedRows === 1 ? 'is' : 'are',
            'count' => number_format($importedRows),
        ]);

        if ($failedRowsCount > 0) {
            $body .= ' ' . __('admin-import.' . ($failedRowsCount === 1 ? 'failed_row_singular' : 'failed_row_plural'), ['count' => $failedRowsCount]);
        }

        return $body;
    }
}
