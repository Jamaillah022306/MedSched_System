<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            abort(403, 'No patient profile found.');
        }

        $upcomingCount = Appointment::where('patient_id', $patient->id)
            ->upcoming()
            ->count();

        $totalVisits = Appointment::where('patient_id', $patient->id)
            ->where('status', 'done')
            ->count();

        $lastVisitCount = Appointment::where('patient_id', $patient->id)
            ->where('status', 'done')
            ->whereDate('appointment_date', '<=', today())
            ->count();

        $upcomingAppointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->upcoming()
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        // Notifications — with queue number info
        $notifications = Appointment::with(['doctor', 'actedBy'])
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['confirmed', 'cancelled', 'pending', 'done'])
            ->latest('updated_at')
            ->take(10)
            ->get();

        // Find if patient is "next in queue" today
        // Check each confirmed appointment of this patient today
        $nextQueueAlert = null;
        $todayConfirmed = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->where('status', 'confirmed')
            ->whereDate('appointment_date', today())
            ->get();

        foreach ($todayConfirmed as $appt) {
            // Find the appointment just before this patient (same doctor, same day, done)
            $previousDone = Appointment::where('doctor_id', $appt->doctor_id)
                ->whereDate('appointment_date', today())
                ->where('status', 'done')
                ->where('queue_number', $appt->queue_number - 1)
                ->exists();

            // If previous queue is done → this patient is next!
            if ($previousDone || $appt->queue_number === 1) {
                // Check if there's someone currently being served (same queue - 1 was just marked done)
                $currentlyServing = Appointment::where('doctor_id', $appt->doctor_id)
                    ->whereDate('appointment_date', today())
                    ->where('status', 'done')
                    ->where('queue_number', $appt->queue_number - 1)
                    ->exists();

                if ($currentlyServing) {
                    $nextQueueAlert = $appt;
                    break;
                }
            }
        }

        return view('patient.dashboard', compact(
            'patient',
            'upcomingCount',
            'totalVisits',
            'lastVisitCount',
            'upcomingAppointments',
            'notifications',
            'nextQueueAlert'
        ));
    }
}