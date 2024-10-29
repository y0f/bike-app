<?php

namespace App\Filament\Mechanic\Resources\ScheduleResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Mechanic\Resources\ScheduleResource;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['owner_id'] = Filament::auth()->user()->id;
        /** @var \App\Models\ServicePoint $servicePoint the auth user's servicePoint */
        $servicePoint = Filament::getTenant();
        $data['service_point_id'] = $servicePoint->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
