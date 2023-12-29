<?php

namespace App\Filament\Mechanic\Resources\LoanBikeResource\Pages;

use App\Filament\Mechanic\Resources\LoanBikeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoanBike extends EditRecord
{
    protected static string $resource = LoanBikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
