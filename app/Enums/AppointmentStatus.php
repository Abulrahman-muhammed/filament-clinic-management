<?php

namespace App\Enums;

enum AppointmentStatus:int
{
    case Pending = 0;
    case Confirmed = 1;
    case CheckedIn = 2;
    case Completed = 3;
    case Cancelled = 4;
    case NoShow = 5;

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::CheckedIn => 'Checked In',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
            self::NoShow => 'No Show',
        };
    }
        public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'info',
            self::CheckedIn => 'success',
            self::Completed => 'gray',
            self::Cancelled => 'danger',
            self::NoShow => 'danger',
        };
    }
}