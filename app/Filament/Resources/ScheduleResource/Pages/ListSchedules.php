<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use Filament\Actions;
use App\Models\Schedule;
use App\Enums\DaysOfTheWeek;
use Illuminate\Support\Carbon;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ScheduleResource;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'Alle roosters' => Tab::make()->badge(Schedule::query()->count()),
        ];

        foreach (DaysOfTheWeek::cases() as $day) {
            $dayLabel = $day->getLabel();

            $tabs[$dayLabel] = Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', $day))
                ->badge(Schedule::query()->where('day_of_the_week', $day)->count() ?: null);
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string | int | null
    {
        $currentDayIndex = Carbon::now()->dayOfWeek;

        foreach (DaysOfTheWeek::cases() as $day) {
            if ($currentDayIndex === $day->value) {
                return $day->getLabel();
            }
        }

        return 'Alle roosters';
    }
}
