<?php

namespace App\Filament\Admin\Resources\ServicePointResource\Pages;

use App\Filament\Admin\Resources\ServicePointResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServicePoint extends CreateRecord
{
    protected static string $resource = ServicePointResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
