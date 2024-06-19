<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;

class NewPlatformUsersThisYear extends ChartWidget
{
    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): string
    {
        return __('filament.registered_accounts_this_year');
    }
    
    protected function getData(): array
    {
        $currentYear = date('Y');

        $newUsers = User::whereYear('created_at', $currentYear)->select('created_at')->get();

        $groupedNewUsers = $newUsers->groupBy(function ($user) {
            return (int)$user->created_at->format('m');
        });

        $monthlyCounts = $groupedNewUsers->map->count();

        $data = [
            'datasets' => [
                [
                    'label' => __('filament.registered_accounts'), 
                    'data' => $this->getMonthlyCounts($monthlyCounts),
                    'fill' => 'start',
                ]
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
