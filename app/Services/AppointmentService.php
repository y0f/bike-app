<?php

namespace App\Services;

use App\Models\Appointment;

class AppointmentService
{
    public static function handleStatusUpdate(Appointment $appointment)
    {
        $originalStatus = $appointment->getOriginal('status');
        $newStatus = $appointment->getAttribute('status');

        if ($originalStatus !== $newStatus) {
            $originalStatusLabel = $originalStatus->getLabel();
            $newStatusLabel = $newStatus->getLabel();

            $note = $appointment->logs()->create([
                'body' => "Afspraak status is gewijzigd van {$originalStatusLabel} naar {$newStatusLabel}",
            ]);
        }
    }
}
