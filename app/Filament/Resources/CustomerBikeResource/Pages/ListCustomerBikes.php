<?php

namespace App\Filament\Resources\CustomerBikeResource\Pages;

use App\Filament\Resources\CustomerBikeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerBikes extends ListRecords
{
    protected static string $resource = CustomerBikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
