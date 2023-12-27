<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum AppointmentStatus: string implements HasLabel, HasColor
{
    case Created    = 'created';
    case Confirmed  = 'confirmed';
    case Cancelled  = 'cancelled';
    case InProgress = 'in_progress';
    case Completed  = 'completed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Created    => 'Aangemaakt',
            self::Confirmed  => 'Bevestigd',
            self::Cancelled  => 'Geannuleerd',
            self::InProgress => 'In Uitvoering',
            self::Completed  => 'Voltooid',
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
}
