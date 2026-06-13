<?php

namespace App\Enums;

enum AppointmentSource:int
{
    case Website = 0;
    case Reception = 1;
    case WalkIn = 2;

    public function label(): string
    {
        return match($this) {
            self::Website => 'Website',
            self::Reception => 'Reception',
            self::WalkIn => 'Walk In',
        };
    }
}