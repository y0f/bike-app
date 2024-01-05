<?php

namespace App\Filament\Owner\Resources\CustomerBikeResource\Pages;

use App\Filament\Owner\Resources\CustomerBikeResource;
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
}
