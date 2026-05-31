<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $guarded = [];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
