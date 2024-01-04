<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use Filament\Actions;
use App\Models\LoanBike;
use App\Models\Appointment;
use App\Enums\LoanBikeStatus;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\AppointmentResource;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record instanceof Appointment) {
            $data['mechanic_id']      = $this->record->slot->schedule->owner_id;
            // Prevents a visual bug where the slot_id shows instead of the formatted time.
            $data['slot_id']          = $this->record->slot->formatted_time;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record instanceof Appointment) {
            // Set the slot_id based on the existing appointment record
            $data['slot_id'] = $this->record->slot->id;

            // Ensure has_loan_bike is set and default to false if not provided
            $data['has_loan_bike'] = $data['has_loan_bike'] ?? false;

            // If has_loan_bike is false, set loan_bike_id to null
            if ($data['has_loan_bike'] == false) {
                $data['loan_bike_id'] = null;
            }
        }

        // original model data to get the old loan_bike_id
        $oldLoanBikeId = $this->record->getOriginal('loan_bike_id');

        if (isset($data['loan_bike_id']) && $data['loan_bike_id'] !== $oldLoanBikeId) {
            $previousLoanBike = LoanBike::find($oldLoanBikeId);

            if ($previousLoanBike) {
                $previousLoanBike->status = LoanBikeStatus::Available;
                $previousLoanBike->save();
            }
        } elseif (!isset($data['loan_bike_id']) && $oldLoanBikeId) {
            // If no loan bike is selected, but there was a previously selected loan bike
            $previousLoanBike = LoanBike::find($oldLoanBikeId);

            if ($previousLoanBike) {
                $previousLoanBike->status = LoanBikeStatus::Available;
                $previousLoanBike->save();
            }
        }

        // Find the new LoanBike and update its status to rented_out
        if (isset($data['loan_bike_id']) && $data['loan_bike_id']) {
            $newLoanBike = LoanBike::find($data['loan_bike_id']);
            if ($newLoanBike) {
                $newLoanBike->status = LoanBikeStatus::RentedOut;
                $newLoanBike->save();
            }
        }

        return $data;
    }
}
