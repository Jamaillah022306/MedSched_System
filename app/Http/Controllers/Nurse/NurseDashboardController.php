<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class NurseDashboardController extends Controller
{
    public function index()
    {
        $appointmentsToday = Appointment::today()->count();
        $pendingCount      = Appointment::pending()->count();
        $totalPatients     = Patient::count();

        $todayAppointments = Appointment::with(['patient', 'doctor'])
            ->today()
            ->orderBy('appointment_time')
            ->get();

        // Notifications — newest pending appointments (new bookings)
        $newAppointments = Appointment::with(['patient.user', 'doctor'])
            ->where('status', 'pending')
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('nurse.dashboard', compact(
            'appointmentsToday',
            'pendingCount',
            'totalPatients',
            'todayAppointments',
            'newAppointments'
        ));
    }

    public function appointments(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);

        // Search by patient name
        if ($request->filled('search')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by doctor
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                              ->paginate(5);

        $doctors = Doctor::all();

        return view('nurse.appointments', compact('appointments', 'doctors'));
    }
}