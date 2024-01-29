<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;

class AppointmentsChart extends ChartWidget
{
    protected static ?string $heading = 'Afgeronde afspraken dit jaar';

    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $currentYear = now()->year;

        // Optimize the query by selecting only the necessary data
        $appointments = Appointment::where('status', AppointmentStatus::Completed)
            ->whereYear('date', $currentYear)
            ->select('date')
            ->get();

        $groupedAppointments = $appointments->groupBy(function ($appointment) {
            return (int)$appointment->date->format('m'); // Convert to integer
        });

        $monthlyCounts = $groupedAppointments->map->count();

        $data = [
            'datasets' => [
                [
                    'label' => 'Afgeronde afspraken',
                    'data' => $this->getMonthlyCounts($monthlyCounts),
                    'fill' => 'start',
                ],
            ],
            'labels' => $this->getMonthLabels(),
        ];

        return $data;
    }

    private function getMonthlyCounts($monthlyCounts)
    {
        $counts = [];
        for ($month = 1; $month <= 12; $month++) {
            $counts[] = $monthlyCounts->get($month, 0); // Use integer key
        }
        return $counts;
    }

    private function getMonthLabels()
    {
        $labels = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec',
        ];

        return $labels;
    }
}