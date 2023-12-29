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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // original model data to get the old loan_bike_id
        $oldLoanBikeId = $this->record->getOriginal('loan_bike_id');

        if ($data['loan_bike_id'] !== $oldLoanBikeId) {
            $previousLoanBike = LoanBike::find($oldLoanBikeId);

            if ($previousLoanBike) {
                $previousLoanBike->status = LoanBikeStatus::Available;
                $previousLoanBike->save();
            }
        }

        // Find the new LoanBike and update its status to RentedOut
        if ($data['loan_bike_id']) {
            $newLoanBike = LoanBike::find($data['loan_bike_id']);
            if ($newLoanBike) {
                $newLoanBike->status = LoanBikeStatus::RentedOut;
                $newLoanBike->save();
            }
        }

        return $data;
    }
}
