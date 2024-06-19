<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum LoanBikeStatus: string implements HasLabel, HasColor
{
    case Available  = 'available';
    case RentedOut  = 'rented_out';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Available    => __('loan_bike_status.available'),
            self::RentedOut    => __('loan_bike_status.rented_out'),
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Available    => 'success',
            self::RentedOut    => 'danger',
        };
    }
}
