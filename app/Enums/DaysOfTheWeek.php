<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DaysOfTheWeek: int implements HasLabel
{
    case Sunday     = 0;
    case Monday     = 1;
    case Tuesday    = 2;
    case Wednesday  = 3;
    case Thursday   = 4;
    case Friday     = 5;
    case Saturday   = 6;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Sunday    => 'Zondag',
            self::Monday    => 'Maandag',
            self::Tuesday   => 'Dinsdag',
            self::Wednesday => 'Woensdag',
            self::Thursday  => 'Donderdag',
            self::Friday    => 'Vrijdag',
            self::Saturday  => 'Zaterdag',
        };
    }
}
