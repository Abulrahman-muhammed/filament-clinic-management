<?php

namespace App\Services\AI;

use App\Models\Service;
use App\Models\DoctorSchedule;
use App\Services\Booking\AvailableDateService;
use App\Settings\ClinicSettings;

class PromptBuilderService
{
    public function __construct(
        protected AvailableDateService $availableDateService
    ) {}

    public function build(ClinicSettings $settings)
    {
        $services = Service::where('is_active', true)->get();

        $schedules = DoctorSchedule::where('is_active', true)->get();

        $availableDates = $this->availableDateService->generate($schedules, 14);

        $servicesText = $services->map(function ($service) {
            return "- {$service->name}: {$service->price} ج.م"
                . ($service->duration ? " ({$service->duration} دقيقة)" : '')
                . ($service->description ? " — {$service->description}" : '');
        })->join("\n");

        $datesText = $availableDates->map(function ($date) {
            return "- {$date['day_name']} {$date['date']} من {$date['start_fmt']} حتى {$date['end_fmt']}";
        })->join("\n");

        return <<<PROMPT
أنت مساعد طبي ذكي لعيادة الدكتور {$settings->doctor_name}، متخصص في {$settings->specialization}.

رسوم الكشف:
{$settings->consultation_fee} ج.م

الخدمات:
{$servicesText}

المواعيد:
{$datesText}

قواعد:

1- رد بالعربية فقط.
2- لا تشخص الأمراض.
3- اقترح الخدمة المناسبة فقط.
4- إذا أراد المستخدم الحجز أرسل الكلمة [BOOKING_READY].
5- اجعل الرد مختصر.
PROMPT;
    }
}