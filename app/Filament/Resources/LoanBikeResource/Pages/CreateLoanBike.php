<?php

namespace App\Filament\Resources\LoanBikeResource\Pages;

use App\Filament\Resources\LoanBikeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoanBike extends CreateRecord
{
    protected static string $resource = LoanBikeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
