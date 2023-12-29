<?php

namespace App\Filament\Mechanic\Resources\AppointmentResource\Pages;

use Filament\Actions;
use App\Models\LoanBike;
use App\Models\Appointment;
use App\Enums\LoanBikeStatus;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Mechanic\Resources\AppointmentResource;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record instanceof Appointment) {
            $data['date']             = $this->record->date;
            $data['mechanic_id']      = $this->record->slot->schedule->owner_id;
            $data['loan_bike_id']     = $this->record->loan_bike_id;
            $data['service_point_id'] = $this->record->service_point_id;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // original model data to get the old loan_bike_id
        $oldLoanBikeId = $this->record->getOriginal('loan_bike_id');

        // Check if 'loan_bike_id' key exists in $data and has changed
        if (array_key_exists('loan_bike_id', $data) && $data['loan_bike_id'] !== $oldLoanBikeId) {
            $previousLoanBike = LoanBike::find($oldLoanBikeId);
            if ($previousLoanBike) {
                $previousLoanBike->status = LoanBikeStatus::Available;
                $previousLoanBike->save();
            }
        }

        // Check if 'loan_bike_id' is not set or is null
        if (!isset($data['loan_bike_id']) || $data['loan_bike_id'] === null) {
            $data['loan_bike_id'] = null;
        }

        // Check if 'loan_bike_id' is set and not equal to the current loan_bike_id
        if (isset($data['loan_bike_id']) && $data['loan_bike_id'] !== $this->record->loan_bike_id) {
            $newLoanBike = LoanBike::find($data['loan_bike_id']);
            if ($newLoanBike) {
                $newLoanBike->status = LoanBikeStatus::RentedOut;
                $newLoanBike->save();
            }
        }

        return $data;
    }
}
