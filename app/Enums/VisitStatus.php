<?php
namespace App\Enums;

enum VisitStatus:int
{
    case Waiting = 0;
    case InProgress = 1;
    case Completed = 2;
}
