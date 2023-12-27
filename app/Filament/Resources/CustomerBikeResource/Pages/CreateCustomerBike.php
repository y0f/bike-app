<?php

namespace App\Filament\Resources\CustomerBikeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CustomerBikeResource;

class CreateCustomerBike extends CreateRecord
{
    protected static string $resource = CustomerBikeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {

    //     // needs to be added if there is a user being created for default value.
    //
    //     return $data;
    // }

}
