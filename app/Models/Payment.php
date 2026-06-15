<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];


    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
