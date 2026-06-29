<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
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

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function visit()
    {
        return $this->hasOne(Visit::class);
    }
    // booted    protected static function booted()
    protected static function booted()
    {
        static::creating(function ($appointment) {
            $appointment->created_by = auth()->id() ?? null;
        });
    }


}

