<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Models\LoanBike;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\AppointmentResource;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['loan_bike_id']) {
            // Updating the status of the selected LoanBike to 'rented_out'
            LoanBike::where('id', $data['loan_bike_id'])->update(['status' => 'rented_out']);
        }

        return $data;
    }
}
