<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;

class SlotService
{
    public function availableFor(Builder $query, User $mechanic, int $dayOfTheWeek, int $servicePointId): void
    {
        $query->whereHas('schedule', function (Builder $query) use ($mechanic, $dayOfTheWeek, $servicePointId) {
            $query
                ->where('service_point_id', $servicePointId)
                ->where('day_of_the_week', $dayOfTheWeek)
                ->whereBelongsTo($mechanic, 'owner');
        })
        ->where('available', true); // Add this line to check availability
    }
}
