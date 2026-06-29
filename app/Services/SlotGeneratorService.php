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
            ->map(fn ($time) => Carbon::parse($time)->format('H:i:s'))
            ->toArray();
    
        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);
    
        $slots = [];
    
        while ($start->lt($end)) {
    
            $time = $start->format('H:i:s');
    
            if (
                in_array($time, $bookedSlots, true) ||
                (Carbon::parse($date)->isToday() && $start->isPast())
            ) {
                $start->addMinutes($schedule->slot_duration);
                continue;
            }
    
            $slots[$time] = $start->format('h:i A');
    
            $start->addMinutes($schedule->slot_duration);
        }
    
        return $slots;
    }


    public function generateForApi(string $date): array
    {
        $slots = $this->generate($date);
    
        return collect($slots)
            ->map(fn ($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->toArray();
    }
}