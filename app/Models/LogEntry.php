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
     * Retrieve the parent loggable model associated with this note.
     *
     * This method defines a polymorphic relationship, allowing the Note model
     * to be associated with various loggable models such as CustomerBike, LoanBike, and Appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}
