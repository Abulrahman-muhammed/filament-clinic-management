<?php

namespace App\Services\Booking;

use App\Enums\AppointmentSource;
use App\Enums\AppointmentStatus;
use App\Enums\PaymentMethod;
use App\Exceptions\SlotAlreadyBookedException;
use App\Models\Appointment;
use App\Settings\ClinicSettings;

class BookingService
{
    public function __construct(
        private readonly AppointmentConflictService $conflictService,
        private readonly PatientLookupService       $patientService,
        private readonly ClinicSettings             $settings,
    ) {}

    /**
     * @throws SlotAlreadyBookedException
     */
    public function book(array $data): Appointment
    {
        if ($this->conflictService->hasConflict($data['appointment_date'], $data['appointment_time'])) {
            throw new SlotAlreadyBookedException();
        }

        $patient = $this->patientService->findOrCreate($data);

        $appointment = $patient->appointments()->create([
            'service_id'       => $data['service_id'],
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time'],
            'notes'            => $data['patient_notes'] ?? null,
            'source'           => AppointmentSource::Website->value,
            'status'           => AppointmentStatus::Pending->value,
        ]);

        if ($this->settings->allow_online_payment && $data['payment_method'] === PaymentMethod::Visa->value) {
            // TODO: initiate Paymob / Stripe session
            // return redirect($this->initiatePayment($appointment));
        }

        return $appointment;
    }
}