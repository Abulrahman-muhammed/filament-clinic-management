<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Gender;
class Patient extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'gender',
        'birth_date',
        'address',
        'notes',
        'created_by',
    ];

    // cast
    protected $casts = [
        'birth_date' => 'date',
    ];



    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

        protected static function booted()
    {
        static::creating(function ($patient) {
            $patient->created_by = auth()->id();
        });
    }
        public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
