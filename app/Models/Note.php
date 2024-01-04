<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['body'];
    
    /**
     * Parent notable model, (CustomerBike, LoanBike, Appointment)
     *
     * @return MorphTo
     */
    public function notable(): MorphTo
    {
        return $this->morphTo();
    }
}
