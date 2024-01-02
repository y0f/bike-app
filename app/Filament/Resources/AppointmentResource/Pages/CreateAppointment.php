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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['loan_bike_id'])) {
            LoanBike::where('id', $data['loan_bike_id'])
                ->update(['status' => LoanBikeStatus::RentedOut]);
        }

        $data['has_loan_bike'] = $data['has_loan_bike'] ?? false;

        return $data;
    }
}
