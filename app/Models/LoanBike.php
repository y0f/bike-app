<?php

namespace App\Models;

use App\Enums\BikeType;
use App\Enums\LoanBikeStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanBike extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'identifier',
        'brand',
        'model',
        'type',
        'image',
        'color',
        'specifications',
        'service_point_id',
        'status',
    ];

    protected $casts = [
       'type'   => BikeType::class,
       'status' => LoanBikeStatus::class,
    ];

    public function servicePoint(): BelongsTo
    {
        return $this->belongsTo(ServicePoint::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
