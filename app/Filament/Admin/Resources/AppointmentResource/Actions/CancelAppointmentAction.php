<?php

namespace App\Filament\Admin\Resources\AppointmentResource\Actions;

use Filament\Tables\Actions\Action;
use App\Enums\AppointmentStatus;
use App\Enums\LoanBikeStatus;
use App\Models\Appointment;

class CancelAppointmentAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->label(__('filament.appointments.cancel'))
            ->action(function (Appointment $record) {
                $record->status = AppointmentStatus::Cancelled;
                $record->has_loan_bike = false;
                $record->loan_bike_id = null;
                $record->save();

                if ($record->loanBike) {
                    $record->loanBike->status = LoanBikeStatus::Available;
                    $record->loanBike->save();
                }
            })
            ->visible(
                fn (Appointment $record) =>
                $record->status !== AppointmentStatus::Cancelled
                && $record->status !== AppointmentStatus::Completed
            )
            ->color('danger')
            ->icon('heroicon-o-x-mark');
    }
}
