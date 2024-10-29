<?php

namespace App\Filament\Mechanic\Resources\LoanBikeResource\Pages;

use App\Filament\Mechanic\Resources\LoanBikeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoanBikes extends ListRecords
{
    protected static string $resource = LoanBikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
