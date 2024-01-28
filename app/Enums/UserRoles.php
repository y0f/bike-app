<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum UserRoles: string implements HasLabel, HasColor, HasIcon
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

    public function getColor(): ?string
    {
        return match ($this) {
            self::Admin        => 'info', 
            self::Staff        => 'warning', 
            self::Mechanic     => 'primary', 
            self::VehicleOwner => 'success', 
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Admin        => 'heroicon-o-user',
            self::Staff        => 'heroicon-o-users', 
            self::Mechanic     => 'heroicon-o-wrench', 
            self::VehicleOwner => 'icon-bike', 
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::Admin        => 'Volledige toegang tot het admin-paneel en alle functionaliteiten. Kan gebruikersbeheer, rapportages en systeeminstellingen beheren.',
            self::Staff        => 'Beperkte toegang tot het admin-paneel. Kan dagelijkse taken uitvoeren, zoals het bijwerken van reparatiestatussen en het beheren van monteur roosters en afspraken van monteurs.',
            self::Mechanic     => 'Toegang tot het monteurs paneel. Kan werkorders bekijken, reparaties plannen en technische details bijwerken. Kan optioneel ook eigen rooster inplannen en maken.',
            self::VehicleOwner => 'Kan afspraken plannen, reparatiestatussen controleren en communiceren met de monteur of staff.',
        };
    }
}
