<?php

namespace App\Filament\Admin\Resources\ScheduleResource\Pages;

use Filament\Actions;
use App\Models\Schedule;
use App\Enums\DaysOfTheWeek;
use Illuminate\Support\Carbon;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\ScheduleResource;

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
        return [
            __('filament.all_schedules') => Tab::make()->badge(Schedule::query()->count()),

            __('filament.days_of_the_week.sunday') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Sunday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Sunday)->count() ?: null),

            __('filament.days_of_the_week.monday') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Monday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Monday)->count() ?: null),

            __('filament.days_of_the_week.tuesday') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Tuesday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Tuesday)->count() ?: null),

            __('filament.days_of_the_week.wednesday') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Wednesday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Wednesday)->count() ?: null),

            __('filament.days_of_the_week.thursday') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Thursday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Thursday)->count() ?: null),

            __('filament.days_of_the_week.friday') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Friday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Friday)->count() ?: null),

            __('filament.days_of_the_week.saturday') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Saturday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Saturday)->count() ?: null),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        $currentDayIndex = Carbon::now()->dayOfWeek;

        foreach (DaysOfTheWeek::cases() as $day) {
            if ($currentDayIndex === $day->value) {
                return __('filament.days_of_the_week' . strtolower($day->getLabel()));
            }
        }

        return __('filament.all_schedules');
    }
}
