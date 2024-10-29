<?php

namespace App\Filament\Admin\Resources\CustomerBikeResource\Pages;

use App\Filament\Admin\Resources\CustomerBikeResource;
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
