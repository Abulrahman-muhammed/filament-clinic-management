<?php
namespace App\Enums;

enum PaymentStatus:int
{
    case Pending = 0;
    case Paid = 1;
    case Refunded = 2;
}