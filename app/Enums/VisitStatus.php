<?php
namespace App\Enums;

enum VisitStatus:int
{
    case Waiting = 0;
    case InProgress = 1;
    case Completed = 2;
        public function label(): string
    {
        return match($this) {
            self::Waiting => 'Waiting',
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Waiting => 'warning',
            self::InProgress => 'info',
            self::Completed => 'success',
        };
    }
}
