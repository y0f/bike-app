<?php

namespace App\Filament\Admin\Resources\ServicePointResource\Pages;

use App\Filament\Admin\Resources\ServicePointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServicePoints extends ListRecords
{
    protected static string $resource = ServicePointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
