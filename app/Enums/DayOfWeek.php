<?php

namespace App\Enums;

enum DayOfWeek:int
{
    case Saturday = 6;
    case Sunday = 0;
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;

    public function label(): string
    {
        return match ($this) {
            self::Saturday => 'Saturday',
            self::Sunday => 'Sunday',
            self::Monday => 'Monday',
            self::Tuesday => 'Tuesday',
            self::Wednesday => 'Wednesday',
            self::Thursday => 'Thursday',
            self::Friday => 'Friday',
        };
    }
}