<?php

namespace App\Filament\Mechanic\Resources\CustomerBikeResource\Pages;

use App\Filament\Mechanic\Resources\CustomerBikeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerBike extends EditRecord
{
    protected static string $resource = CustomerBikeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->outlined()
            ->icon('heroicon-o-trash'),
            Actions\Action::make('Resetten')
            ->outlined()
            ->icon('heroicon-o-arrow-path')
            ->action(fn () => $this->fillForm())
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
