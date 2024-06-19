<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Illuminate\Support\Collection;

enum AppointmentStatus: string implements HasLabel, HasColor, HasIcon
{
    // We only use Created, Cancelled and Completed for now but the other two will eventually be used.

    case Created    = 'created';
    case Confirmed  = 'confirmed';
    case Cancelled  = 'cancelled';
    case InProgress = 'in_progress';
    case Completed  = 'completed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Created    => __('filament.appointment_status.created'),
            self::Confirmed  => __('filament.appointment_status.confirmed'),
            self::Cancelled  => __('filament.appointment_status.cancelled'),
            self::InProgress => __('filament.appointment_status.in_progress'),
            self::Completed  => __('filament.appointment_status.completed'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Created    => 'gray',
            self::Confirmed  => 'info',
            self::Cancelled  => 'danger',
            self::InProgress => 'warning',
            self::Completed  => 'success',
        };
    }

    public function getIcon(): string | null
    {
        return match ($this) {
            self::Created    => 'heroicon-o-check',
            self::Confirmed  => 'heroicon-o-question-mark-circle',
            self::Cancelled  => 'heroicon-o-x-circle',
            self::InProgress => 'heroicon-o-clock',
            self::Completed  => 'heroicon-o-check-circle',
        };
    }

    public static function statuses(): Collection
    {
        return collect(self::cases())->map(function ($case) {
            return [
                'id' => $case->value,
                'title' => $case->getLabel(),
            ];
        });
    }

}
