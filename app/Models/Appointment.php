<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'slot_id',
        'service_point_id',
        'mechanic_id',
        'date',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => AppointmentStatus::class,
        'date' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function (Appointment $appointment) {
            $originalStatus = $appointment->getOriginal('status');
            $newStatus = $appointment->getAttribute('status');

            if ($originalStatus !== $newStatus) {
                Log::info("Afspraak status is gewijzigd van {$originalStatus->value} naar {$newStatus->value}", [
                    'appointment_id' => $appointment->id,
                ]);
            }
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

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function servicePoint(): BelongsTo
    {
        return $this->belongsTo(ServicePoint::class);
    }
}
