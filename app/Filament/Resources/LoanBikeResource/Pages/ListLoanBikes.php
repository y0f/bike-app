<?php

namespace App\Filament\Resources\LoanBikeResource\Pages;

use Filament\Actions;
use App\Enums\UserRoles;
use Filament\Facades\Filament;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Exports\LoanBikeExporter;
use App\Filament\Imports\LoanBikeImporter;
use App\Filament\Resources\LoanBikeResource;

class ListLoanBikes extends ListRecords
{
    protected static string $resource = LoanBikeResource::class;

    protected function getHeaderActions(): array
    {
        $user = Filament::auth()->user();

        $actions = [
            Actions\CreateAction::make()
                ->color('primary')
                ->icon('icon-bike'),
        ];

        // Check if the user is an admin, and then add the import action
        if ((string)$user->role_id === UserRoles::Admin->value) {
            $actions[] = ImportAction::make()
                ->label('Leenmiddelen importeren')
                ->color('primary')
                ->icon('heroicon-o-arrow-down-on-square-stack')
                ->importer(LoanBikeImporter::class);

            $actions[] =  ExportAction::make()
                ->label('Leenmiddelen exporteren')
                ->color('primary')
                ->icon('heroicon-o-clipboard-document-list')
                ->exporter(LoanBikeExporter::class);
        }



        return $actions;
    }
}
