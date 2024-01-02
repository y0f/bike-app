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
        $totalAppointmentsLast30Days = Appointment::where('created_at', '>', now()->subDays(30))->count();
        $completedAppointmentsLast30Days = Appointment::where('created_at', '>', now()->subDays(30))
            ->where('status', 'completed')->count();

        // Calculate completion percentage for the last 30 days
        $completionPercentage = $totalAppointmentsLast30Days > 0 ? ($completedAppointmentsLast30Days / $totalAppointmentsLast30Days) * 100 : 0;

        // Calculate total appointments for the current month
        $totalAppointmentsCurrentMonth = Appointment::whereMonth('created_at', now()->format('m'))->count();

        // Calculate total appointments for the previous month
        $totalAppointmentsPreviousMonth = Appointment::whereMonth('created_at', now()->subMonth()->format('m'))->count();

        // Calculate the number of new appointments added in the current month
        $newAppointmentsThisMonth = $totalAppointmentsCurrentMonth - $totalAppointmentsPreviousMonth;

        // Calculate percentage change in new appointments compared to the previous month
        $percentageNewAppointments = $totalAppointmentsPreviousMonth > 0 ? (($newAppointmentsThisMonth / $totalAppointmentsPreviousMonth) * 100) : 100;

        return [
            Stat::make('Voltooide afspraken laatste 30 dagen', number_format($completedAppointmentsLast30Days))
                ->description(number_format($completionPercentage, 2) . '%')
                ->descriptionIcon($completionPercentage >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($completionPercentage >= 0 ? 'success' : 'danger'),

            Stat::make('Nieuwe afspraken deze maand', number_format($newAppointmentsThisMonth))
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
