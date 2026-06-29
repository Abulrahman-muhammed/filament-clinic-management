<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Patient;
use  App\Settings\ClinicSettings;
use App\Http\Requests\BookingRequest;
use App\Services\SlotGeneratorService;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
class BookingController extends Controller
{
   public function index()
    {
        $clinicSettings = app(ClinicSettings::class);
        $services       = Service::orderBy('name')->get();
        $schedules      = DoctorSchedule::orderBy('day_of_week')->where('is_active', true)->get();
        $doctorSchedule = DoctorSchedule::where('is_active', true)->where('day_of_week', Carbon::today()->dayOfWeekIso)->first();
 $availableDates = collect();

foreach ($schedules as $s) {
    $dayOfWeek = \App\Enums\DayOfWeek::from($s->day_of_week); // e.g. "monday"
    
    // الـ next occurrence من النهارده
    $date = now()->startOfDay();
    
    // لو النهارده نفس اليوم، ابدأ من بكره
    $targetDow = $dayOfWeek->carbonDayOfWeek(); // method هتضيفها
    
    $daysUntil = ($targetDow - $date->dayOfWeek + 7) % 7;
    if ($daysUntil === 0) $daysUntil = 7; // مش النهارده نفسه
    
    $date->addDays($daysUntil);
    
    $availableDates->push([
        'date'      => $date->format('Y-m-d'),
        'day_name'  => $dayOfWeek->arLabel(),
        'formatted' => $date->translatedFormat('j M'), // أو format عادي
        'start_fmt' => \Carbon\Carbon::parse($s->start_time)->format('g A'),
        'end_fmt'   => \Carbon\Carbon::parse($s->end_time)->format('g A'),
        'schedule'  => $s,
    ]);
}

// مرتبة بالتاريخ
$availableDates = $availableDates->sortBy('date')->values();
        return view('user.booking.index', compact(
            'clinicSettings',
            'services',
            'schedules',
            'doctorSchedule',
            'availableDates',
        ));
    }
 
    /**
     * Return available time slots for a given date (AJAX).
     * GET /booking/slots?date=2025-06-15
     */
    public function slots(Request $request, SlotGeneratorService $generator)
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);
 
        $slots = $generator->generateForApi($request->date);
 
        return response()->json([
            'slots' => $slots,   // [['value'=>'09:00:00','label'=>'09:00 AM'], ...]
        ]);
    }
 
    /**
     * Store a new appointment.
     * All validation is handled by BookingRequest — no manual validation needed here.
     */
    public function store(BookingRequest $request): RedirectResponse
    {
        $data = $request->validated();
 
        // ── Duplicate-slot check ──────────────────────────────────────────
        $conflict = Appointment::where('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $data['appointment_time'])
            ->whereNotIn('status', ['cancelled'])
            ->exists();
 
        if ($conflict) {
            return back()
                ->withInput()
                ->with('error', 'هذا الموعد محجوز بالفعل. يرجى اختيار وقت آخر.');
        }
 
        // ── Create the appointment ────────────────────────────────────────
        Appointment::create([
            'user_id'            => auth()->id(),
            'service_id'         => $data['service_id'],
            'patient_name'       => $data['patient_name'],
            'patient_phone'      => $data['patient_phone'],
            'patient_email'      => $data['patient_email'],
            'patient_dob'        => $data['patient_dob']         ?? null,
            'patient_gender'     => $data['patient_gender']      ?? null,
            'patient_blood_type' => $data['patient_blood_type']  ?? null,
            'patient_notes'      => $data['patient_notes']       ?? null,
            'appointment_date'   => $data['appointment_date'],
            'appointment_time'   => $data['appointment_time'],
            'payment_method'     => $data['payment_method'],
            'status'             => 'pending',
        ]);
 
        // ── Payment redirect (card) ───────────────────────────────────────
        if ($data['payment_method'] === 'card') {
            // TODO: initiate Stripe / Paymob session and redirect
            // return redirect($checkoutUrl);
        }
 
        return redirect()
            ->route('user.booking.index')
            ->with('success', 'تم تأكيد حجزك بنجاح! سنتواصل معك قريباً.');
    }
}
