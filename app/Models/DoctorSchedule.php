<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
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

    // accessor to get if doctor is available for the appointment date and time
    public function getIsAvailableTodayAttribute(): bool
    {
        return $this->is_active
            && $this->day_of_week === Carbon::today()->dayOfWeekIso;
    }

    // accessor to get the start time in 12 hour format
    public function getStartTimeFormattedAttribute(): string
    {
        return Carbon::parse($this->start_time)->format('h:i A');
    }

    // accessor to get the end time in 12 hour format
    public function getEndTimeFormattedAttribute(): string
    {
        return Carbon::parse($this->end_time)->format('h:i A');
    }

}
