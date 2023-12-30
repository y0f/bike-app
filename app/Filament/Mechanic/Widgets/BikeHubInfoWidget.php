<?php

namespace Filament\Widgets;

class BikeHubInfoWidget extends Widget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'filament.mechanic.pages.bikehub-info-widget';
}
