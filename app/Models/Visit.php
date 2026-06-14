<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'patient_id',
        'appointment_id',
        'status',
        'notes',
        'visited_at',
    ];
public function patient()
{
    return $this->belongsTo(Patient::class);
}

public function appointment()
{
    return $this->belongsTo(Appointment::class);
}

public function prescriptions()
{
    return $this->hasMany(Prescription::class);
}
}
