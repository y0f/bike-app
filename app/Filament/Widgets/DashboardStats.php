<?php

namespace App\Filament\Widgets;

use App\Filament\Stats\AnotherStat;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Stats\NewCustomersStat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DashboardStats extends BaseWidget
{
    protected $newCustomersStat;
    protected $anotherStat;

    public function __construct()
    {
        $this->newCustomersStat = new NewCustomersStat();
        $this->anotherStat = new AnotherStat();
    }

    protected function getStats(): array
    {
        $combinedStats = [];

        $newCustomersStats = $this->newCustomersStat->getStats();
        $combinedStats = array_merge($combinedStats, $newCustomersStats);

        $anotherStats = $this->anotherStat->getStats();
        $combinedStats = array_merge($combinedStats, $anotherStats);

        return $combinedStats;
    }
}
