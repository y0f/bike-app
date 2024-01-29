<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformUsersOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('TODO', '1')
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('TODO', '1')
                ->description('3% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([17, 1, 14, 1, 14, 13, 23])
                ->color('danger'),
            Stat::make('TODO', '1')
                ->description('7% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
        ];
    }
}
