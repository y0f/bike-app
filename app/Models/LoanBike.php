<?php

namespace App\Models;

use App\Enums\BikeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'year_build',
        'color',
        'specifications',
        'service_point_id',
    ];

    protected $casts = [
       'type' => BikeType::class,
    ];

    public function servicePoint(): BelongsTo
    {
        return $this->belongsTo(ServicePoint::class);
    }
}

