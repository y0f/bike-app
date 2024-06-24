<?php

namespace App\Filament\Admin\Pages;

use JibayMcs\FilamentTour\Tour\Step;
use JibayMcs\FilamentTour\Tour\Tour;
use JibayMcs\FilamentTour\Tour\HasTour;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    use HasTour;

    public function tours(): array
    {
        return [
            Tour::make('dashboard')
                ->steps(

                    Step::make()
                        ->title("Welcome to your Dashboard!")
                        ->description(view('tutorial.dashboard.introduction')),

                    Step::make('button.fi-btn.relative.grid-flow-col.items-center.justify-center.font-semibold.outline-none.transition.duration-75.focus-visible\:ring-2.rounded-lg.fi-btn-color-gray.fi-color-gray.fi-size-md.fi-btn-size-md.gap-1\.5.px-3.py-2.text-sm.hidden.sm\:inline-grid.shadow-sm.bg-white.text-gray-950.hover\:bg-gray-50.dark\:bg-white\/5.dark\:text-white.dark\:hover\:bg-white\/10.ring-1.ring-gray-950\/10.dark\:ring-white\/20 > span.fi-btn-label')
                        ->title('CSSS SELECTOR GOT THIS')
                        ->description('TEST')
                        ->icon('heroicon-o-calendar')
                        ->iconColor('primary'),

                    Step::make('.fi-avatar')
                        ->title('Woaw! Here is your avatar!')
                        ->description('You look nice!')
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary'),

                    Step::make('.fi-wi-stats-overview-stat')
                        ->title('A TODO Stat, test!')
                        ->description('Test!')
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary'),

                    Step::make('.fi-sidebar-nav')
                        ->title('Here is the entire navigation!')
                        ->description('Test!')
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary'),

                    // Specific targeting within groups
                    Step::make('.fi-sidebar-group:nth-of-type(2) li.fi-sidebar-item:nth-of-type(1) a')
                        ->title('Appointments')
                        ->description('Manage appointments here')
                        ->icon('heroicon-o-calendar')
                        ->iconColor('primary'),

                    Step::make('.fi-sidebar-group:nth-of-type(2) li.fi-sidebar-item:nth-of-type(2) a')
                        ->title('Mechanic Schedules')
                        ->description('Manage mechanic schedules here')
                        ->icon('heroicon-o-clipboard')
                        ->iconColor('primary'),

                    Step::make('.fi-sidebar-group:nth-of-type(3) li.fi-sidebar-item:nth-of-type(1) a')
                        ->title('Service Points')
                        ->description('Manage service points here')
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary'),

                    Step::make('.fi-sidebar-group:nth-of-type(3) li.fi-sidebar-item:nth-of-type(2) a')
                        ->title('Inventory Items')
                        ->description('Manage inventory items here')
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary'),

                    Step::make('.fi-sidebar-group:nth-of-type(3) li.fi-sidebar-item:nth-of-type(3) a')
                        ->title('Customer Bikes')
                        ->description('View customer bikes here')
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary'),

                    Step::make('.fi-sidebar-group:nth-of-type(3) li.fi-sidebar-item:nth-of-type(4) a')
                        ->title('Loan Bikes')
                        ->description('Manage loan bikes here')
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary'),

                    Step::make('.fi-sidebar-group:nth-of-type(4) li.fi-sidebar-item:nth-of-type(1) a')
                        ->title('Users')
                        ->description('Manage users here')
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary'),

                    Step::make('.fi-sidebar-group:nth-of-type(4) li.fi-sidebar-item:nth-of-type(2) a')
                        ->title('Activity Log')
                        ->description('View activity log here')
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary')
                ),
        ];
    }
}

