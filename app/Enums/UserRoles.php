<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRoles: string implements HasLabel
{
    case Admin           = '1';
    case Staff           = '4';
    case Mechanic        = '2';
    case VehicleOwner    = '3';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Admin        => 'Administrator',
            self::Staff        => 'Manager',
            self::Mechanic     => 'Monteur',
            self::VehicleOwner => 'Gebruiker',
        };
    }
}
