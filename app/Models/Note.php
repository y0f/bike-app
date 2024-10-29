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
     * Retrieve the parent notable model associated with this note.
     *
     * This method defines a polymorphic relationship, allowing the Note model
     * to be associated with various notable models such as CustomerBike, LoanBike, and Appointment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function notable(): MorphTo
    {
        return $this->morphTo();
    }
}
