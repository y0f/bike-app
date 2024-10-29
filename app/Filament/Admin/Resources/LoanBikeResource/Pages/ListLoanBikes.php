<?php

namespace App\Filament\Admin\Resources\LoanBikeResource\Pages;

use Filament\Actions;
use App\Enums\UserRoles;
use Filament\Facades\Filament;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Exports\LoanBikeExporter;
use App\Filament\Admin\Imports\LoanBikeImporter;
use App\Filament\Admin\Resources\LoanBikeResource;

class ListLoanBikes extends ListRecords
{
    protected static string $resource = LoanBikeResource::class;

    protected function getHeaderActions(): array
    {
        $user = Filament::auth()->user();

        $actions = [
            Actions\CreateAction::make()
                ->label(__('filament.loan_bikes.create'))
                ->color('primary')
                ->icon(__('filament.loan_bikes.create_icon')),
        ];

        // Check if the user is an admin, and then add the import and export actions
        if ((string)$user->role_id === UserRoles::Admin->value) {
            $actions[] = ImportAction::make()
                ->label(__('filament.loan_bikes.import'))
                ->color('primary')
                ->icon(__('filament.loan_bikes.import_icon'))
                ->importer(LoanBikeImporter::class);

            $actions[] = ExportAction::make()
                ->label(__('filament.loan_bikes.export'))
                ->color('primary')
                ->icon(__('filament.loan_bikes.export_icon'))
                ->exporter(LoanBikeExporter::class);
        }

        return $actions;
    }
}
