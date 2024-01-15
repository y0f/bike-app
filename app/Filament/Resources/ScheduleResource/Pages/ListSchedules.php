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
        return [
            'Alle roosters' => Tab::make()->badge(Schedule::query()->count()),
            DaysOfTheWeek::Sunday->getLabel() => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Sunday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Sunday)->count() ?: null),

            DaysOfTheWeek::Monday->getLabel()  => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Monday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Monday)->count() ?: null),

            DaysOfTheWeek::Tuesday->getLabel() => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Tuesday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Tuesday)->count() ?: null),

            DaysOfTheWeek::Wednesday->getLabel() => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Wednesday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Wednesday)->count() ?: null),

            DaysOfTheWeek::Thursday->getLabel() => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Thursday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Thursday)->count() ?: null),

            DaysOfTheWeek::Friday->getLabel() => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Friday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Friday)->count() ?: null),

            DaysOfTheWeek::Saturday->getLabel() => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Saturday))
                ->badge(Schedule::query()->where('day_of_the_week', DaysOfTheWeek::Saturday)->count() ?: null),
        ];
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
