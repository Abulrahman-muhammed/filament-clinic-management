<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Carbon\Carbon;

class SlotGeneratorService
{
    public function generate(string $date): array
    {
        $dayNumber = Carbon::parse($date)->dayOfWeek;

        $schedule = DoctorSchedule::query()
            ->where('day_of_week', $dayNumber)
            ->where('is_active', true)
            ->first();

        if (! $schedule) {
            return [];
        }

        $bookedSlots = Appointment::query()
            ->whereDate('appointment_date', $date)
            ->pluck('appointment_time')
            ->toArray();

        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);

        $slots = [];

        $isToday = Carbon::parse($date)->isToday();

        while ($start->lt($end)) {

            $time = $start->format('H:i:s'); // Format current time as 'H:i:s' for comparison

            if (
                in_array($time, $bookedSlots)
            ) {
                $start->addMinutes($schedule->slot_duration);
                continue;
            }

            if ($isToday && $start->lessThan(now())) {
                $start->addMinutes($schedule->slot_duration);
                continue;
            }

            $slots[$time] = $start->format('h:i A'); // Store the time in 'H:i:s' format as the key and 'h:i A' format as the value

            $start->addMinutes($schedule->slot_duration);
        }


        return $slots;
    }
}