<?php

namespace App\Filament\Admin\Resources\LoanBikeResource\Pages;

use App\Filament\Admin\Resources\LoanBikeResource;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
