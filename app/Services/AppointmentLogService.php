<?php

namespace App\Services;

use App\Models\LoanBike;
use App\Models\Appointment;

class AppointmentLogService
{
    public static function handleStatusUpdate(Appointment $appointment)
    {
        $fieldsToCheck = [
            'description', 
            'loan_bike_id', 
            'date', 
            'has_loan_bike', 
            'status'
        ];

        foreach ($fieldsToCheck as $field) {
            $originalValue = $appointment->getOriginal($field);
            $newValue = $appointment->getAttribute($field);

            $message = self::generateMessage($field, $originalValue, $newValue);

            if (!empty($message)) {
                $appointment->logs()->create(['body' => $message]);
            }
        }
    }

    private static function generateMessage($field, $originalValue, $newValue)
    {
        switch ($field) {
            case 'status':
                return self::generateStatusMessage($originalValue, $newValue);

            case 'description':
                return self::generateSimpleMessage($field, $originalValue, $newValue);

            case 'loan_bike_id':
                return self::generateLoanBikeMessage($originalValue, $newValue);

            case 'date':
                return self::generateSimpleMessage($field, date('Y-m-d', strtotime($originalValue)), date('Y-m-d', strtotime($newValue)));

            case 'has_loan_bike':
                return self::generateSimpleMessage($field, $originalValue ? 'Ja' : 'Nee', $newValue ? 'Ja' : 'Nee');

            default:
                return '';
        }
    }

    private static function generateStatusMessage($originalValue, $newValue)
    {
        $originalStatusLabel = $originalValue->getLabel();
        $newStatusLabel = $newValue->getLabel();

        return ($originalStatusLabel !== $newStatusLabel)
            ? "Afspraak status is gewijzigd van {$originalStatusLabel} naar {$newStatusLabel}"
            : '';
    }

    private static function generateLoanBikeMessage($originalValue, $newValue)
    {
        $originalLoanBikeIdentifier = $originalValue ? LoanBike::find($originalValue)->identifier : null;
        $newLoanBikeIdentifier = $newValue ? LoanBike::find($newValue)->identifier : null;

        if ($originalValue === null && $newValue !== null) {
            return "Afspraak heeft nu een leenfiets (Leenfiets ID: {$newLoanBikeIdentifier})";
        } elseif ($originalValue !== null && $newValue === null) {
            return "Afspraak heeft geen leenfiets meer (Leenfiets is ontkoppeld)";
        } elseif ($originalValue !== null && $newValue !== null && $originalLoanBikeIdentifier !== $newLoanBikeIdentifier) {
            return "Afspraak leenfiets is gewijzigd van {$originalLoanBikeIdentifier} naar {$newLoanBikeIdentifier}";
        }

        return '';
    }


    private static function generateSimpleMessage($field, $originalValue, $newValue)
    {
        $fieldLabel = ($field === 'date') ? 'datum' : (($field === 'has_loan_bike') ? 'heeft leenfiets' : $field);

        return ($originalValue !== $newValue)
            ? "Afspraak {$fieldLabel} is gewijzigd van {$originalValue} naar {$newValue}"
            : '';
    }
}
