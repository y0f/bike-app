<?php

namespace App\Observers;

use App\Models\Appointment;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        $mechanic = $appointment->mechanic;
        $tenant = $appointment->service_point_id;

        Notification::make()
            ->title('Nieuwe afspraak!')
            ->body('van ' . $appointment->customerBike->owner->name . ' op ' . $appointment->date->toDateString('d-m-y'))
            ->icon('heroicon-o-document-text')
            ->iconColor('primary')
            ->actions([
                Action::make('Bekijken')
                    ->button()
                    ->url(route('filament.mechanic.resources.afspraken.index', ['tenant' => $tenant]), shouldOpenInNewTab: true),
            ])
            ->sendToDatabase($mechanic);
    }
}
