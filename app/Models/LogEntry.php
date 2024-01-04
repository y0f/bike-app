<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogEntry extends Model
{
    use HasFactory;

    protected $fillable = ['body'];
    
    /**
     * Parent notable model, (CustomerBike, LoanBike, Appointment)
     *
     * @return MorphTo
     */
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}
