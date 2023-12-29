<?php

namespace App\Filament\Mechanic\Resources\AppointmentResource\Pages;

use Filament\Actions;
use App\Models\LoanBike;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Mechanic\Resources\AppointmentResource;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['loan_bike_id'])) {
            LoanBike::where('id', $data['loan_bike_id'])
            ->update(['status' => 'rented_out']);
        }

        return $data;
    }
}
