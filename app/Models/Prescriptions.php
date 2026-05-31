<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescriptions extends Model
{
    protected $guarded = [];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
