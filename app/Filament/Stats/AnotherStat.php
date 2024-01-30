<?php

namespace App\Filament\Stats;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnotherStat extends BaseWidget
{
    // protected int|string|array $columnSpan = '2xl';

    protected function getStats(): array
    {
        return [
            Stat::make('TODO', '1')
                ->description('3% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([17, 1, 14, 1, 14, 13, 23])
                ->color('danger'),
        ];
    }
}
