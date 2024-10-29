<?php

namespace App\Filament\Mechanic\Resources\ScheduleResource\Pages;

use App\Filament\Mechanic\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

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
