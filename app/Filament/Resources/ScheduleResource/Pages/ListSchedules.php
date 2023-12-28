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
            'Alle roosters' => Tab::make()
                ->badge(Schedule::query()->count()),

            'Zondag' => Tab::make()
                ->label('Zondag')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Sunday)),

            'Maandag' => Tab::make()
                ->label('Maandag')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Monday)),

            'Dinsdag' => Tab::make()
                ->label('Dinsdag')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Tuesday)),

            'Woensdag' => Tab::make()
                ->label('Woensdag')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Wednesday)),

            'Donderdag' => Tab::make()
                ->label('Donderdag')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Thursday)),

            'Vrijdag' => Tab::make()
                ->label('Vrijdag')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Friday)),

            'Zaterdag' => Tab::make()
                ->label('Zaterdag')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day_of_the_week', DaysOfTheWeek::Saturday)),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        $currentDayEnglish = Carbon::now()->englishDayOfWeek;

        switch (strtolower($currentDayEnglish)) {
            case 'monday':
                return 'Maandag';
            case 'tuesday':
                return 'Dinsdag';
            case 'wednesday':
                return 'Woensdag';
            case 'thursday':
                return 'Donderdag';
            case 'friday':
                return 'Vrijdag';
            case 'saturday':
                return 'Zaterdag';
            case 'sunday':
                return 'Zondag';
            default:
                return 'Alle roosters';
        }
    }
}
