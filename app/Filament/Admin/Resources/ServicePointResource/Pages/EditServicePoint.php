<?php

namespace App\Filament\Admin\Resources\ServicePointResource\Pages;

use App\Filament\Admin\Resources\ServicePointResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServicePoint extends EditRecord
{
    protected static string $resource = ServicePointResource::class;

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
