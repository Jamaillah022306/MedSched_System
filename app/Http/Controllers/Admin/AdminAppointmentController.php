<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAppointmentController extends Controller
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

        return view('admin.appointments', compact('appointments', 'doctors'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'schedule', 'actedBy']);
        return view('admin.appointment_show', compact('appointment'));
    }

    public function confirm(Appointment $appointment)
    {
        $appointment->update([
            'status'   => 'confirmed',
            'acted_by' => Auth::id(),
        ]);
        return back()->with('success', 'Appointment confirmed.');
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

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('success', 'Appointment deleted.');
    }

    public function archive(Appointment $appointment)
    {
        $appointment->update([
            'status'   => 'archived',
            'acted_by' => Auth::id(),
        ]);

        return back()->with('success', 'Appointment archived successfully.');
    }

    public function archived(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor'])
            ->where('is_archived', true)
            ->latest('archived_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%");
            });
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $appointments = $query->paginate(5);
        $doctors      = Doctor::orderBy('last_name')->get();

        return view('admin.appointments_archived', compact('appointments', 'doctors'));
    }
}