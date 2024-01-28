<?php

namespace App\Filament\Resources\LoanBikeResource\Pages;

use Filament\Actions;
use App\Enums\UserRoles;
use Filament\Facades\Filament;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
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
                ->label('Middelen importeren')
                ->color('primary')
                ->icon('icon-bike')
                ->importer(LoanBikeImporter::class);
        }

        return $actions;
    }
}
