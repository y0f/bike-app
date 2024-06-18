<?php

namespace App\Filament\Widgets\Stats;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnotherStat extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        return [
            Stat::make("", '')
                ->description('TODO: Repair registration module for mechanics to create quotations, use inventoryitemsresource.')
                ->descriptionIcon('heroicon-o-exclamation-circle'),
            Stat::make("", '')
                ->description('TODO: Implement translationfiles from config')
                ->descriptionIcon('heroicon-o-exclamation-circle')
        ];
    }
}
