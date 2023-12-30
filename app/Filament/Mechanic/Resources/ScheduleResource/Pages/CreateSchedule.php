<?php

namespace App\Filament\Mechanic\Resources\ScheduleResource\Pages;

use App\Models\Schedule;
use App\Enums\DaysOfTheWeek;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Mechanic\Resources\ScheduleResource;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;


    // we need to somehow retrieve the date here, revert the date field migration from slots
    // add it to the schedule, create a schedule with a initial date depending on the day of the week chosen
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['owner_id'] = Filament::auth()->user()->id;
        $data['service_point_id'] = Filament::getTenant()->id;
        $slotsData = $this->form->getRawState()['slots'];
    
        // Extract the selected day of the week
        $selectedDayOfWeek = $data['day_of_the_week'];
    
        // Get the current date
        $startDate = Carbon::now();
    
        // Find the next occurrence of the selected day of the week
        $daysToAdd = ($selectedDayOfWeek - $startDate->dayOfWeek + 7) % 7;
        $startDate->addDays($daysToAdd);
    
        // Make sure we're not in the past
        if ($startDate->isPast()) {
            $startDate->addWeek();
        }
    
        // Create one schedule for each selected day of the week
        for ($i = 0; $i < 6 * 4; $i++) { // 4 weeks in a month
            // Create Schedule for the selected day
            $schedule = Schedule::create([
                'owner_id' => $data['owner_id'],
                'day_of_the_week' => $selectedDayOfWeek,
                'service_point_id' => $data['service_point_id'],
            ]);
    
            // Create slots for the schedule using the specified start and end times
            foreach ($slotsData as $uuid => $slot) {
                $start = $slot['start'];
                $end = $slot['end'];
    
                // Create Slot associated with the Schedule
                $schedule->slots()->create([
                    'start' => $start,
                    'end' => $end,
                    'status' => 'available',
                    'date' => $startDate->copy()->format('Y-m-d'),
                ]);
            }
    
            // Move to the next occurrence of the selected day
            $startDate->addWeek();
        }
    
        return $data;
    }
    



    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
