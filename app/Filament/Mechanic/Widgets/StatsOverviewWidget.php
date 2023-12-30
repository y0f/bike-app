<?php

namespace App\Filament\Mechanic\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Calculate total and completed appointments for the last 30 days
        $totalAppointments = Appointment::where('created_at', '>', now()->subDays(30))->count();
        $completedAppointments = Appointment::where('created_at', '>', now()->subDays(30))
                                          ->where('status', 'completed')->count();

        // Calculate completion percentage
        $completionPercentage = $totalAppointments > 0 ? ($completedAppointments / $totalAppointments) * 100 : 0;

        // Calculate total appointments from the previous month and percentage of new appointments
        $totalAppointmentsPreviousMonth = Appointment::whereMonth('created_at', strval(now()->subMonth()->month))->count();
        $percentageNewAppointments = $totalAppointmentsPreviousMonth > 0 ? ($totalAppointments / $totalAppointmentsPreviousMonth) * 100 : 0;

        return [
            Stat::make('Voltooide afspraken deze maand', number_format($completedAppointments))
                ->description(number_format($completionPercentage, 2) . '%')
                ->descriptionIcon($completionPercentage >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($completionPercentage >= 0 ? 'success' : 'danger'),

            Stat::make('Nieuwe afspraken deze maand', number_format($totalAppointments))
                ->description(number_format($percentageNewAppointments, 2) . '%')
                ->descriptionIcon($percentageNewAppointments >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($percentageNewAppointments >= 0 ? 'success' : 'warning'),
        ];
    }

    protected function getColumns(): int
    {
        return 2;
    }
}
