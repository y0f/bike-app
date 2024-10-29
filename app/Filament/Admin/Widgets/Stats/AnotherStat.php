<?php

namespace App\Filament\Admin\Widgets\Stats;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class AnotherStat extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Handy links', '')
                ->description(new HtmlString(
                    '<span class="block mb-2 underline"><a href="https://v2.filamentphp.com/tricks" target="_blank">Filament tricks</a></span>
                    <span class="block mb-2 underline"><a href="https://dev.to/snehalkadwe/filament-v3-with-laravel-10-3h9k" target="_blank">Filament V3 with Laravel 10 - DEV.to</a></span>'
                )),



            Stat::make('TODOs', '')
                ->description(new HtmlString('<div class="text-yellow-500">
                    <span class="block mb-2">- Implement translation files from config for every panel Panel.</span>
                    <span class="block mb-2">- Repair registration module for mechanics to create quotations, using InventoryItemsResource.</span>
                    <span class="block mb-2">- Use more RelationManagers in resources for better UX.</span>
                    <span class="block mb-2">- Create views for bike owners to request repairs and remove the user panel as it is not needed.</span>
                </div>')),


        ];
    }
}
