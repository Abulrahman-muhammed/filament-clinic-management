<?php
namespace App\Enums;

enum PaymentMethod:int
{
    case Cash = 0;
    case Visa = 1;
    case VodafoneCash = 2;
    case InstaPay = 3;

    public function label(): string
    {
        return match($this) {
            self::Cash => 'Cash',
            self::Visa => 'Visa',
            self::VodafoneCash => 'Vodafone Cash',
            self::InstaPay => 'InstaPay',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Cash => 'secondary',
            self::Visa => 'primary',
            self::VodafoneCash => 'danger',
            self::InstaPay => 'success',
        };
    }
}