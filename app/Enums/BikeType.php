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
            self::Sport      => 'Sport Bike',
            self::Cruiser    => 'Cruiser',
            self::Touring    => 'Touring Bike',
            self::DirtBike   => 'Dirt Bike',
            self::Scooter    => 'Scooter',
            self::Naked      => 'Naked Bike',
            self::Electric   => 'Electric Bike',
            self::Hybrid     => 'Hybrid Bike',
            self::Other      => 'Other',
        };
    }
}
