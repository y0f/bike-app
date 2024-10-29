<?php

namespace App\Filament\Admin\Resources\InventoryItemResource\Pages;

use App\Filament\Admin\Resources\InventoryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryItem extends EditRecord
{
    protected static string $resource = InventoryItemResource::class;

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
