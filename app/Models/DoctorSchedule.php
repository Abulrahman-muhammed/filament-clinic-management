<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
        'slot_duration',
        'is_active',
    ];      

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
