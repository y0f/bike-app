<?php

namespace App\Filament\Mechanic\Resources\AppointmentResource\Pages;

use App\Models\LoanBike;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Mechanic\Resources\AppointmentResource;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['mechanic_id'] = Filament::auth()->user()->id;

        if (isset($data['loan_bike_id'])) {
            LoanBike::where('id', $data['loan_bike_id'])
                ->update(['status' => 'rented_out']);
        }

        $data['has_loan_bike'] = $data['has_loan_bike'] ?? false;

        return $data;
    }
}
