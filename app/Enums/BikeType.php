<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BikeType: string implements HasLabel
{
    case Sport       = 'sport';
    case Cruiser     = 'cruiser';
    case Touring     = 'touring';
    case DirtBike    = 'dirt_bike';
    case Scooter     = 'scooter';
    case Naked       = 'naked';
    case Electric    = 'electric';
    case Hybrid      = 'hybrid';
    case Other       = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Sport      => 'Sportfiets',
            self::Cruiser    => 'Cruiser',
            self::Touring    => 'Toerfiets',
            self::DirtBike   => 'Mountainbike',
            self::Scooter    => 'Scooter',
            self::Naked      => 'Naked Bike',
            self::Electric   => 'Elektrische Fiets',
            self::Hybrid     => 'Hybride Fiets',
            self::Other      => 'Overig',
        };
    }
}
