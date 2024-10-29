<?php

namespace App\Models;

use App\Enums\BikeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CustomerBike extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'brand',
        'model',
        'type',
        'image',
        'color',
        'specifications',
        'owner_id',
    ];

    protected $casts = [
       'type' => BikeType::class,
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function servicePoints(): BelongsToMany
    {
        return $this->belongsToMany(ServicePoint::class);
    }

    public function notes(): MorphMany
    {
        return $this->MorphMany(Note::class, 'notable');
    }
}
