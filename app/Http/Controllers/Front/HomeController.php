<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
use App\Models\Service;
class HomeController extends Controller
{
    public function index()
    {
        // clinic stats
        $patientsCount = Patient::count();
        $doctorSchedule = DoctorSchedule::where('is_active', true)->where('day_of_week', Carbon::today()->dayOfWeekIso)->first();
        $services = Service::all();
        return view('user.home', compact('patientsCount', 'doctorSchedule', 'services'));
    }
}
