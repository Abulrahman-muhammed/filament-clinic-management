<?php

namespace App\Http\Controllers\Front;

use App\Exceptions\SlotAlreadyBookedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Requests\BookingSlotsRequest;
use App\Models\DoctorSchedule;
use App\Models\Service;
use App\Services\Booking\AvailableDateService;
use App\Services\Booking\BookingService;
use App\Services\Booking\PatientLookupService;
use App\Services\SlotGeneratorService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(AvailableDateService $availableDateService): View
    {
        $schedules = DoctorSchedule::orderBy('day_of_week')
            ->where('is_active', true)
            ->get();

        return view('user.booking.index', [
            'services'       => Service::where('is_active', true)->orderBy('name')->get(),
            'schedules'      => $schedules,
            'doctorSchedule' => $schedules->firstWhere('day_of_week', Carbon::today()->dayOfWeekIso),
            'availableDates' => $availableDateService->generate($schedules),
        ]);
    }

    public function slots(BookingSlotsRequest $request, SlotGeneratorService $generator): JsonResponse
    {
        return response()->json([
            'slots' => $generator->generateForApi($request->validated('date')),
        ]);
    }

    public function store(BookingRequest $request, BookingService $bookingService): RedirectResponse
    {
        try {
            $bookingService->book($request->validated());

            return redirect()
                ->route('user.booking.index')
                ->with('success', 'تم تأكيد حجزك بنجاح! سنتواصل معك قريباً.');

        } catch (SlotAlreadyBookedException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function patientLookup(Request $request, PatientLookupService $patientService): JsonResponse
    {
        $request->validate(['phone' => ['required']]);

        $patient = $patientService->lookup($request->phone);

        if (! $patient) {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists'  => true,
            'patient' => [
                'name'       => $patient->name,
                'address'    => $patient->address,
                'birth_date' => optional($patient->birth_date)->format('Y-m-d'),
                'gender'     => $patient->gender,
            ],
        ]);
    }
}