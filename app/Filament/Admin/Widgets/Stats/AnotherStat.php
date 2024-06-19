<?php

namespace App\Filament\Admin\Widgets\Stats;

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
                ->description('TODO: Implement translationfiles from config for mechanic panel and owner panel. Use more relationmanagers in resources. Create views for bike-owner to request repair there, remove that logic from the user panel as the user does not need a panel.')
                ->descriptionIcon('heroicon-o-exclamation-circle')
        ];
    }
}
