<?php

namespace App\Filament\Resources\LoanBikeResource\Pages;

use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\LoanBikeImporter;
use App\Filament\Resources\LoanBikeResource;

class ListLoanBikes extends ListRecords
{
    protected static string $resource = LoanBikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->label('Middelen importeren')
                ->color('primary')
                ->icon('icon-bike')
                ->importer(LoanBikeImporter::class),
            Actions\CreateAction::make()
                ->color('primary')
                ->icon('icon-bike'),
        ];
    }
}
