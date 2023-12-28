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
            $data['date'] = $this->record->date;
            $data['mechanic'] = $this->record->slot->schedule->owner_id;
            $data['loan_bike_id'] = $this->record->loan_bike_id;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Check if the loan_bike_id is being updated
        if ($this->record instanceof Appointment && isset($data['loan_bike_id']) && $data['loan_bike_id'] !== $this->record->loan_bike_id) {
            if ($this->record->loan_bike_id) {
                $previousLoanBike = LoanBike::find($this->record->loan_bike_id);
                if ($previousLoanBike) {
                    $previousLoanBike->status = LoanBikeStatus::Available;
                    $previousLoanBike->save();
                }
            }

            // Updating the status of the newly selected LoanBike to 'rented_out'
            if ($data['loan_bike_id']) {
                $newLoanBike = LoanBike::find($data['loan_bike_id']);
                if ($newLoanBike) {
                    $newLoanBike->status = LoanBikeStatus::RentedOut;
                    $newLoanBike->save();
                }
            }
        }

        return $data;
    }
}
