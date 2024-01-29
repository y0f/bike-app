<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServicePoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'zip',
        'phone'
    ];

    protected $casts = [
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function customerBikes(): BelongsToMany
    {
        return $this->belongsToMany(CustomerBike::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function loanBikes(): HasMany
    {
        return $this->hasMany(LoanBike::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(LoanBike::class);
    }
}
