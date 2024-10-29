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
            self::Sunday    => __('filament.days_of_the_week.sunday'),
            self::Monday    => __('filament.days_of_the_week.monday'),
            self::Tuesday   => __('filament.days_of_the_week.tuesday'),
            self::Wednesday => __('filament.days_of_the_week.wednesday'),
            self::Thursday  => __('filament.days_of_the_week.thursday'),
            self::Friday    => __('filament.days_of_the_week.friday'),
            self::Saturday  => __('filament.days_of_the_week.saturday'),
        };
    }
}
