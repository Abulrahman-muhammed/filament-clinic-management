<?php

namespace App\Services\Booking;

use App\Enums\DayOfWeek;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailableDateService
{
    public function generate(Collection $schedules, int $days = 14): Collection
    {
        $availableDates = collect();

        for ($i = 0; $i < $days; $i++) {

            $date = now()->startOfDay()->addDays($i);

            $schedule = $schedules->first(function ($schedule) use ($date) {

                return DayOfWeek::from($schedule->day_of_week)->carbonDayOfWeek()
                    === $date->dayOfWeek;

            });

            if (! $schedule) {
                continue;
            }

            $availableDates->push([
                'date'       => $date->format('Y-m-d'),
                'day_name'   => DayOfWeek::from($schedule->day_of_week)->arLabel(),
                'formatted'  => $date->translatedFormat('d M'),
                'start_time' => $schedule->start_time,
                'end_time'   => $schedule->end_time,
                'start_fmt'  => Carbon::parse($schedule->start_time)->format('g:i A'),
                'end_fmt'    => Carbon::parse($schedule->end_time)->format('g:i A'),
            ]);
        }

        return $availableDates->values();
    }
}