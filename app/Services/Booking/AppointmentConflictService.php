<?php

namespace App\Services\Booking;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;

class AppointmentConflictService
{
    public function hasConflict(string $date, string $time): bool
    {
        return Appointment::query()
            ->where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->whereNotIn('status', [AppointmentStatus::Cancelled->value])
            ->exists();
    }
}