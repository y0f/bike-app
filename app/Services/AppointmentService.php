<?php

namespace App\Services;

use App\Models\Appointment;

class AppointmentService
{
    public static function handleStatusUpdate(Appointment $appointment)
    {

        $fieldsToCheck = ['description', 'loan_bike_id', 'date', 'has_loan_bike', 'status'];

        foreach ($fieldsToCheck as $field) {

            $originalValue = $appointment->getOriginal($field);
            $newValue = $appointment->getAttribute($field);

            $message = ''; 

            switch ($field) {
                
                case 'status':
                    $originalStatusLabel = $originalValue->getLabel();
                    $newStatusLabel = $newValue->getLabel();
                    if ($originalStatusLabel !== $newStatusLabel) {
                        $message = "Afspraak status is gewijzigd van {$originalStatusLabel} naar {$newStatusLabel}";
                    }
                    break;

                case 'description':
                    $message = "Afspraak {$field} is gewijzigd van {$originalValue} naar {$newValue}";
                    break;

                case 'loan_bike_id':
                    $originalLoanBikeId = $originalValue ? $appointment->loanBike->id : null;
                    $newLoanBikeId = $newValue ? $appointment->loanBike->id : null;

                    if ($originalLoanBikeId === null && $newLoanBikeId !== null) {
                        $message = "Afspraak heeft nu een leenfiets (Leenfiets ID: {$newLoanBikeId})";
                    } elseif ($originalLoanBikeId !== null && $newLoanBikeId === null) {
                        $message = "Afspraak heeft geen leenfiets meer (Leenfiets is ontkoppeld)";
                    } elseif ($originalLoanBikeId !== null && $newLoanBikeId !== null && $originalLoanBikeId !== $newLoanBikeId) {
                        $message = "Afspraak leenfiets is gewijzigd van {$originalLoanBikeId} naar {$newLoanBikeId}";
                    }
                    break;

                case 'date':
                    $originalLabel = date('Y-m-d', strtotime($originalValue));
                    $newLabel = date('Y-m-d', strtotime($newValue));
                    if ($originalLabel !== $newLabel) {
                        $message = "Afspraak {$field} is gewijzigd van {$originalLabel} naar {$newLabel}";
                    }
                    break;

                case 'has_loan_bike':
                    $originalLabel = $originalValue ? 'Ja' : 'Nee';
                    $newLabel = $newValue ? 'Ja' : 'Nee';
                    if ($originalValue !== $newValue) {
                        $message = "Afspraak {$field} is gewijzigd van {$originalLabel} naar {$newLabel}";
                    }
                    break;
            }

            if (!empty($message)) {
                $appointment->logs()->create([
                    'body' => $message,
                ]);
            }
        }
    }
}
