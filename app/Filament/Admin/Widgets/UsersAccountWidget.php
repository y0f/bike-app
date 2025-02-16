<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\AccountWidget;

class UsersAccountWidget extends AccountWidget
{
    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    /**
     * @var view-string
     */
    protected static string $view = 'filament-panels::widgets.account-widget';
}
