<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Models\LoanBike;
use App\Enums\LoanBikeStatus;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\AppointmentResource;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
