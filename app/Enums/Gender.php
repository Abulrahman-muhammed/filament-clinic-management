<?php

namespace App\Enums;

enum Gender:int
{
    case Male = 1;
    case Female = 2;

    public function label(): string
    {
        return match($this) {
            self::Male => 'Male',
            self::Female => 'Female',
        };
    }

    // ar label
    public function arlabel(): string
    {
        return match($this) {
            self::Male => 'ذكر',
            self::Female => 'انثى',
        };
    }
    public function color(): string
    {
        return match($this) {
            self::Male => 'primary',
            self::Female => 'secondary',
        };
    }
}