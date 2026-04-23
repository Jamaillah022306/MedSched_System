<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NurseAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->paginate(5);
        $doctors      = Doctor::orderBy('last_name')->get();

        return view('nurse.appointments', compact('appointments', 'doctors'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.schedules', 'schedule', 'actedBy']);
        return view('nurse.appointment_show', compact('appointment'));
    }

    public function confirm(Appointment $appointment)
    {
        $lastQueue = Appointment::where('doctor_id', $appointment->doctor_id)
            ->whereDate('appointment_date', $appointment->appointment_date)
            ->whereNotNull('queue_number')
            ->max('queue_number');

        $queueNumber = ($lastQueue ?? 0) + 1;

        $appointment->update([
            'status'       => 'confirmed',
            'acted_by'     => Auth::id(),
            'queue_number' => $queueNumber,
        ]);

        return back()->with('success', "Appointment confirmed. Queue #$queueNumber assigned.");
    }

    public function done(Appointment $appointment)
    {
        $appointment->update([
            'status'   => 'done',
            'acted_by' => Auth::id(),
        ]);

        return back()->with('success', 'Appointment marked as done.');
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ]);

        $appointment->update([
            'status'        => 'cancelled',
            'cancel_reason' => $request->cancel_reason,
            'acted_by'      => Auth::id(),
        ]);

        return back()->with('success', 'Appointment cancelled.');
    }

    public function archive(Appointment $appointment)
    {
        $appointment->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);

        return back()->with('success', 'Appointment archived.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('success', 'Appointment deleted.');
    }
}