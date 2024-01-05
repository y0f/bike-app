<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Utils\TranslationUtils;
use Filament\Resources\Pages\ViewRecord;

class ViewActivity extends ViewRecord
{
    public static function getResource(): string
    {
        return config('filament-logger.activity_resource');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $fieldTranslations = trans('activity-log-fields');

        // Translate all fields
        foreach ($fieldTranslations as $field => $translation) {
            if (isset($data[$field])) {
                $data[$field] = $translation;
            }
        }

        if (isset($data['description'])) {
            $translatedDescription = TranslationUtils::translateWords($data['description'], $fieldTranslations);
            $data['description'] = $translatedDescription;
        }

        // Translate subject_type if needed
        if (isset($data['subject_type'])) {
            $modelTranslations = trans('models');
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
