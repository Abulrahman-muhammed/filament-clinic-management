<?php

namespace App\Services\Booking;

use App\Models\Patient;

class PatientLookupService
{
    public function findOrCreate(array $data): Patient
    {
        return Patient::updateOrCreate(
            ['phone' => $data['patient_phone']],
            [
                'name'       => $data['patient_name'],
                'address'    => $data['patient_address']  ?? null,
                'birth_date' => $data['patient_dob']      ?? null,
                'gender'     => $data['patient_gender']   ?? null,
            ]
        );
    }

    public function lookup(string $phone): ?Patient
    {
        return Patient::where('phone', $phone)->first();
    }
}