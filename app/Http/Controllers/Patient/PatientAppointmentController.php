<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientAppointmentController extends Controller
{
    // ─── My Appointments ─────────────────────────────────────────────────────

    public function index()
    {
        $patient = Auth::user()->patient;

        $appointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->latest('appointment_date')
            ->paginate(15);

        return view('patient.appointments', compact('appointments'));
    }

    // ─── Appointment Slip ─────────────────────────────────────────────────────

    public function slip(Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        // Make sure this appointment belongs to the logged-in patient
        if ($appointment->patient_id !== $patient->id) {
            abort(403);
        }

        $appointment->load(['patient.user', 'doctor', 'actedBy']);

        return view('patient.appointment_slip', compact('appointment'));
    }

    // ─── Book Appointment Form ────────────────────────────────────────────────

    public function create()
    {
        $doctors = Doctor::with('schedules')
            ->whereHas('schedules', fn($q) => $q->where('is_available', true))
            ->orderBy('last_name')
            ->get();

        $bookedSlots = [];
        $allBooked = Appointment::whereIn('status', ['Pending', 'Confirmed'])
            ->whereDate('appointment_date', '>=', today())
            ->get(['doctor_id', 'appointment_date', 'appointment_time']);

        foreach ($allBooked as $appt) {
            $docId = $appt->doctor_id;
            $date  = $appt->appointment_date->format('Y-m-d');
            $time  = \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A');
            $bookedSlots[$docId][$date][] = $time;
        }

        $doctorSchedules = [];
        foreach ($doctors as $doctor) {
            foreach ($doctor->schedules as $schedule) {
                if ($schedule->is_available) {
                    $doctorSchedules[$doctor->id][$schedule->day_of_week] = $schedule->generateTimeSlots();
                }
            }
        }

        return view('patient.book', compact('doctors', 'bookedSlots', 'doctorSchedules'));
    }

    // ─── Store Booking ────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $request->validate([
            'doctor_id'        => ['required', 'exists:doctors,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required'],
            'type'             => ['required', 'string'],
            'reason'           => ['nullable', 'string', 'max:1000'],
        ]);

        $patient = Auth::user()->patient;
        $doctor  = Doctor::with('schedules')->findOrFail($data['doctor_id']);

        $dayName = strtolower(\Carbon\Carbon::parse($data['appointment_date'])->format('l'));

        if (!$doctor->isAvailableOn($dayName)) {
            return back()->withInput()->withErrors([
                'appointment_date' => "{$doctor->name} is not available on " . ucfirst($dayName) . ".",
            ]);
        }

        $schedule = $doctor->schedules()
            ->where('day_of_week', $dayName)
            ->where('is_available', true)
            ->first();

        $timeFormatted = \Carbon\Carbon::createFromFormat('h:i A', $data['appointment_time'])->format('H:i:s');

        $alreadyBooked = Appointment::where('doctor_id', $data['doctor_id'])
            ->whereDate('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $timeFormatted)
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->exists();

        if ($alreadyBooked) {
            return back()->withInput()->withErrors([
                'appointment_time' => 'That time slot is already taken. Please choose another.',
            ]);
        }

        // Get the next queue number for that doctor on that date
        $lastQueue = Appointment::where('doctor_id', $data['doctor_id'])
            ->whereDate('appointment_date', $data['appointment_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->max('queue_number');

        $queueNumber = ($lastQueue ?? 0) + 1;

        Appointment::create([
            'patient_id'       => $patient->id,
            'doctor_id'        => $data['doctor_id'],
            'schedule_id'      => $schedule?->id,
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $timeFormatted,
            'type'             => $data['type'],
            'reason'           => $data['reason'],
            'status'           => 'Pending',
            'queue_number'     => $queueNumber,
        ]);

        return redirect()
            ->route('patient.appointments')
            ->with('success', 'Appointment booked! Please wait for confirmation.');
    }
}