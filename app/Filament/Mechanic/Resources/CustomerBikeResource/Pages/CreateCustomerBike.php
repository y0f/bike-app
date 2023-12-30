<?php

namespace App\Filament\Mechanic\Resources\CustomerBikeResource\Pages;

use App\Filament\Mechanic\Resources\CustomerBikeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerBike extends CreateRecord
{
    protected static string $resource = CustomerBikeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
