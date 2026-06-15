<?php
namespace App\Enums;

enum PaymentStatus:int
{
    case Pending = 0;
    case Paid = 1;
    case Refunded = 2;

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Paid => 'Paid',
            self::Refunded => 'Refunded',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'warning',
            self::Paid => 'success',
            self::Refunded => 'danger',
        };
    }
}