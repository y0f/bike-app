<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\DaysOfTheWeek;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'service_point_id',
        'day_of_the_week',
        'date',
        // TODO: holiday_start, holiday_end, update SlotService to use these dates.
    ];

    protected $casts = [
        'date'            => 'datetime',
        'day_of_the_week' => DaysOfTheWeek::class,
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function servicePoint(): BelongsTo
    {
        return $this->belongsTo(ServicePoint::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class);
    }

    public function customerBike(): BelongsTo
    {
        return $this->belongsTo(CustomerBike::class);
    }

    public function getDateForDayOfWeekAttribute(): Carbon
    {
        $dayOfWeek = $this->day_of_the_week->value;

        $dayDifference = ($dayOfWeek - now()->dayOfWeek + 7) % 7;

        return now()->addDays($dayDifference);
    }
}
