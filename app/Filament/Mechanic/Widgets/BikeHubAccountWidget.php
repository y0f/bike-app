<?php

namespace Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class BikeHubAccountWidget extends BaseWidget
{
    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'filament-panels::widgets.account-widget';

    public function getColumnSpan(): int | string | array
    {
        return 1;
    }
}
