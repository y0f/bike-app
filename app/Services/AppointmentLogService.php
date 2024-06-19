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
                return self::generateSimpleMessage('description', $originalValue, $newValue);

            case 'loan_bike_id':
                return self::generateLoanBikeMessage($originalValue, $newValue);

            case 'date':
                return self::generateSimpleMessage('date', date('Y-m-d', strtotime($originalValue)), date('Y-m-d', strtotime($newValue)));

            case 'has_loan_bike':
                return self::generateSimpleMessage('has_loan_bike', $originalValue ? __('filament.appointments.logs.yes') : __('filament.appointments.logs.no'), $newValue ? __('filament.appointments.logs.yes') : __('filament.appointments.logs.no'));

            default:
                return '';
        }
    }

    private static function generateStatusMessage($originalValue, $newValue)
    {
        $originalStatusLabel = $originalValue->getLabel();
        $newStatusLabel = $newValue->getLabel();

        return ($originalStatusLabel !== $newStatusLabel)
            ? __('filament.appointments.logs.status_changed', ['from' => $originalStatusLabel, 'to' => $newStatusLabel])
            : '';
    }

    private static function generateLoanBikeMessage($originalValue, $newValue)
    {
        $originalLoanBikeIdentifier = $originalValue ? LoanBike::find($originalValue)->identifier : null;
        $newLoanBikeIdentifier = $newValue ? LoanBike::find($newValue)->identifier : null;

        if ($originalValue === null && $newValue !== null) {
            return __('filament.appointments.logs.loan_bike_added', ['id' => $newLoanBikeIdentifier]);
        } elseif ($originalValue !== null && $newValue === null) {
            return __('filament.appointments.logs.loan_bike_removed');
        } elseif ($originalValue !== null && $newValue !== null && $originalLoanBikeIdentifier !== $newLoanBikeIdentifier) {
            return __('filament.appointments.logs.loan_bike_changed', ['from' => $originalLoanBikeIdentifier, 'to' => $newLoanBikeIdentifier]);
        }

        return '';
    }

    private static function generateSimpleMessage($field, $originalValue, $newValue)
    {
        $fieldLabel = __('filament.appointments.logs.' . $field);

        return ($originalValue !== $newValue)
            ? __('filament.appointments.logs.simple_changed', ['field' => $fieldLabel, 'from' => $originalValue, 'to' => $newValue])
            : '';
    }
}
