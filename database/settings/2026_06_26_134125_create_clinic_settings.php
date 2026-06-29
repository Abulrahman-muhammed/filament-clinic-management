<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('clinic.clinic_name', 'LifeCare Clinic');
        $this->migrator->add('clinic.logo', null);
        $this->migrator->add('clinic.phone', '');
        $this->migrator->add('clinic.whatsapp', '');
        $this->migrator->add('clinic.email', '');
        $this->migrator->add('clinic.address', '');
        $this->migrator->add('clinic.google_maps', '');

        $this->migrator->add('clinic.doctor_name', '');
        $this->migrator->add('clinic.doctor_image', null);
        $this->migrator->add('clinic.specialization', '');
        $this->migrator->add('clinic.experience_years', 0);
        $this->migrator->add('clinic.consultation_fee', 0);
        $this->migrator->add('clinic.working_hours', '');
        
        $this->migrator->add('clinic.allow_booking', true);
        $this->migrator->add('clinic.allow_ai', true);
        $this->migrator->add('clinic.allow_online_payment', false);
    }
};
