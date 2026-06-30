<?php

namespace App\Exceptions;

use Exception;

class SlotAlreadyBookedException extends Exception
{
    public function __construct()
    {
        parent::__construct('هذا الموعد محجوز بالفعل. يرجى اختيار وقت آخر.');
    }
}
