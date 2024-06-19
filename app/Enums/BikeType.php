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
            self::Sport       => __('biketype.sport'),
            self::Cruiser     => __('biketype.cruiser'),
            self::Touring     => __('biketype.touring'),
            self::DirtBike    => __('biketype.dirt_bike'),
            self::Scooter     => __('biketype.scooter'),
            self::Naked       => __('biketype.naked'),
            self::Electric    => __('biketype.electric'),
            self::Hybrid      => __('biketype.hybrid'),
            self::Other       => __('biketype.other'),
        };
    }
}
