<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ClinicSettings extends Settings
{
    public string $clinic_name;
    public ?string $logo;

    public string $phone;
    public string $whatsapp;
    public string $email;
    public string $address;
    public ?string $google_maps;

    public string $doctor_name;
    public ?string $doctor_image;
    public string $specialization;
    public string $working_hours;
    public int $experience_years;

    public float $consultation_fee;

    public bool $allow_booking;
    public bool $allow_ai;
    public bool $allow_online_payment;

    public static function group(): string
    {
        return 'clinic';
    }
}