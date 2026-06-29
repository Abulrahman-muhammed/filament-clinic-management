<?php

namespace App\Enums;

enum DayOfWeek:int
{

    case Monday    = 1;
    case Tuesday   = 2;
    case Wednesday = 3;
    case Thursday  = 4;
    case Friday    = 5;
    case Saturday  = 6; 
    case Sunday    = 7;

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

    public function arLabel(): string
    {
        return match ($this) {
            self::Saturday => 'السبت',
            self::Sunday => 'الأحد',
            self::Monday => 'الإثنين',
            self::Tuesday => 'الثلاثاء',
            self::Wednesday => 'الأربعاء',
            self::Thursday => 'الخميس',
            self::Friday => 'الجمعة',
        };
    }
    
    public function carbonDayOfWeek(): int
{
    return match($this) {
        self::Sunday    => 0,
        self::Monday    => 1,
        self::Tuesday   => 2,
        self::Wednesday => 3,
        self::Thursday  => 4,
        self::Friday    => 5,
        self::Saturday  => 6,
    };
}
}