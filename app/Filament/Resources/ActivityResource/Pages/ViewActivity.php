<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use Filament\Resources\Pages\ViewRecord;

class ViewActivity extends ViewRecord
{
    public static function getResource(): string
    {
        return config('filament-logger.activity_resource');
    }

    // User should not be able to edit this or create this, so this serves just for translating purposes
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $fieldTranslations = trans('activity-log-fields');
        $modelTranslations = trans('models');

        // Translate all fields
        foreach ($fieldTranslations as $field => $translation) {
            if (isset($data[$field])) {
                $data[$field] = $translation;
            }
        }

        // Translate subject_type if needed
        if (isset($data['subject_type'])) {
            $translatedSubjectType = $modelTranslations[$data['subject_type']] ?? $data['subject_type'];
            $data['subject_type'] = $translatedSubjectType;
        }

        // Translate the status if needed
        if (isset($data['properties']['status'])) {
            $translatedStatus = trans("activity-log-fields.{$data['properties']['status']}");
            $data['properties']['status'] = $translatedStatus;
        }

       return $data;
    }
}
