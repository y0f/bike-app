<?php

namespace App\Filament\Mechanic\Resources\ScheduleResource\Pages;

use Filament\Actions;
use App\Models\Schedule;
use App\Enums\DaysOfTheWeek;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Mechanic\Resources\ScheduleResource;

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
        // We need the auth user here to guarantee it's not leaking other tenant data, because there is an issue with the global scope on tab reload.
        $user = Filament::auth()->user();

        $tabs = [
            'Alle roosters' => Tab::make()->badge(Schedule::query()->count()),
        ];

        foreach (DaysOfTheWeek::cases() as $day) {
            $tabs[$day->getLabel()] = Tab::make()
                ->label($day->getLabel())
                ->badge(Schedule::query()->where('day_of_the_week', $day->value)->where('owner_id', $user->id)->count() ?: null)
                ->modifyQueryUsing(function (Builder $query) use ($user, $day) {
                    $query->where('day_of_the_week', $day->value)
                        ->where('owner_id', $user->id);
                });
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
