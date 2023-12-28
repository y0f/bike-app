<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end'   => 'datetime',
    ];

    public function appointment(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function scopeAvailableFor(Builder $query, User $mechanic, int $dayOfTheWeek, int $servicePointId): void
    {
        $query->whereHas('schedule', function (Builder $query) use ($mechanic, $dayOfTheWeek, $servicePointId) {
            $query
                ->where('service_point_id', $servicePointId)
                ->where('day_of_the_week', $dayOfTheWeek)
                ->whereBelongsTo($mechanic, 'owner');
        });
    }

    protected function formattedTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) =>
                Carbon::parse($attributes['start'])->format('H:i') . ' - ' .
                Carbon::parse($attributes['end'])->format('H:i')
        );
    }
}
