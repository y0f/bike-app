<?php

namespace App\Filament\Admin\Resources\CustomerBikeResource\Pages;

use App\Filament\Admin\Resources\CustomerBikeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerBike extends EditRecord
{
    protected static string $resource = CustomerBikeResource::class;

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
