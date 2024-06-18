<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Stats\AnotherStat;
use App\Filament\Widgets\Stats\NewCustomersStat;
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
        return [...$this->newCustomersStat->getStats(), ...$this->anotherStat->getStats()];
    }
}
