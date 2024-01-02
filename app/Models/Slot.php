<?php

namespace App\Models;

use App\Services\SlotService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'schedule_id',
    ];

    protected $casts = [
        'start'     => 'datetime',
        'end'       => 'datetime',
    ];

    public function appointment(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function scopeAvailableFor(Builder $query, User $mechanic, int $dayOfTheWeek, int $servicePointId, Carbon $date): void
    {
        app(SlotService::class)->availableFor($query, $mechanic, $dayOfTheWeek, $servicePointId, $date);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function formattedTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) =>
                Carbon::parse($attributes['start'])->format('h:i') . ' - ' .
                Carbon::parse($attributes['end'])->format('h:i')
        );
    }
}
