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
                    Step::make('.fi-avatar')
                        ->title('Profile Settings')
                        ->description('Here is your avatar. You can manage your account settings here.')
                        ->icon('heroicon-o-user-circle')
                        ->iconColor('primary'),
                    Step::make('.fi-wi-stats-overview-stat')
                        ->title('View Current Statistics')
                        ->description('Check out your completed appointments, new customers, and registered accounts for this year.')
                        ->icon('heroicon-o-chart-bar')
                        ->iconColor('primary'),
                    Step::make('.fi-sidebar-nav')
                        ->title('Navigation Menu')
                        ->description('Use this menu to access all features of the admin dashboard.')
                        ->icon('heroicon-o-bars-3')
                        ->iconColor('primary'),

                    // Specific targeting within groups
                    Step::make('.fi-sidebar-group:nth-of-type(2) li.fi-sidebar-item:nth-of-type(1) a')
                        ->title('Appointments')
                        ->description('Manage and view all customer appointments here.')
                        ->icon('heroicon-o-calendar')
                        ->iconColor('primary'),
                    Step::make('.fi-sidebar-group:nth-of-type(2) li.fi-sidebar-item:nth-of-type(2) a')
                        ->title('Mechanic Schedules')
                        ->description('View and manage mechanic schedules for efficient task distribution.')
                        ->icon('heroicon-o-clipboard')
                        ->iconColor('primary'),
                    Step::make('.fi-sidebar-group:nth-of-type(3) li.fi-sidebar-item:nth-of-type(1) a')
                        ->title('Service Points')
                        ->description('Manage all service points, including various bike repair locations.')
                        ->icon('heroicon-o-map-pin')
                        ->iconColor('primary'),
                    Step::make('.fi-sidebar-group:nth-of-type(3) li.fi-sidebar-item:nth-of-type(2) a')
                        ->title('Inventory Items')
                        ->description('Track and manage items in your inventory, such as bike parts and accessories.')
                        ->icon('heroicon-o-cube')
                        ->iconColor('primary'),
                    Step::make('.fi-sidebar-group:nth-of-type(3) li.fi-sidebar-item:nth-of-type(3) a')
                        ->title('Customer Bikes')
                        ->description('View and manage bikes registered by customers for repair or service.')
                        ->icon('heroicon-o-clipboard')
                        ->iconColor('primary'),
                    Step::make('.fi-sidebar-group:nth-of-type(3) li.fi-sidebar-item:nth-of-type(4) a')
                        ->title('Loan Bikes')
                        ->description('Manage bikes that are available for loan while repairs are underway.')
                        ->icon('heroicon-o-briefcase')
                        ->iconColor('primary'),
                    Step::make('.fi-sidebar-group:nth-of-type(4) li.fi-sidebar-item:nth-of-type(1) a')
                        ->title('Users')
                        ->description('Manage users, including admin and mechanics, on the platform.')
                        ->icon('heroicon-o-user-group')
                        ->iconColor('primary'),
                    Step::make('.fi-sidebar-group:nth-of-type(4) li.fi-sidebar-item:nth-of-type(2) a')
                        ->title('Activity Log')
                        ->description('View the activity log for all actions taken in the system.')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('primary')
                ),
        ];
    }
}
