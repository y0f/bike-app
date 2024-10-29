<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Appointment;
use App\Enums\AppointmentStatus;

class AppointmentsChart extends ChartWidget
{
    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        return __('filament.completed_appointments_this_year');
    }

    protected function getData(): array
    {
        $currentYear = now()->year;

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
                    'label' => __('filament.completed_appointments'),
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
            $counts[] = $monthlyCounts->get($month, 0);
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
