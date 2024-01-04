<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use App\Services\AppointmentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_bike_id',
        'service_point_id',
        'slot_id',
        'mechanic_id',
        'loan_bike_id',
        'date',
        'description',
        'status',
        'has_loan_bike',
    ];

    protected $casts = [
        'status'        => AppointmentStatus::class,
        'date'          => 'datetime',
        'has_loan_bike' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function (Appointment $appointment) {
            AppointmentService::handleStatusUpdate($appointment);
        });
    }

    public function customerBike(): BelongsTo
    {
        return $this->belongsTo(CustomerBike::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }

    public function loanBike(): BelongsTo
    {
        return $this->belongsTo(LoanBike::class, 'loan_bike_id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function servicePoint(): BelongsTo
    {
        return $this->belongsTo(ServicePoint::class);
    }

    public function notes(): MorphMany
    {
        return $this->MorphMany(Note::class, 'notable');
    }

    public function logs(): MorphMany
    {
        return $this->morphMany(LogEntry::class, 'loggable');
    }


    /**
     * Scope a query to include only new appointments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeNew(Builder $query): void
    {
        $query->whereStatus(AppointmentStatus::Created);
    }
}
