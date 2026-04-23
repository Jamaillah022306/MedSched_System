<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('schedules')->orderBy('last_name')->get();
        return view('admin.doctors', compact('doctors'));
    }

    // ─── Store New Doctor ─────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:200'],
            'specialization' => ['required', 'string', 'max:150'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'schedule'       => ['nullable', 'array'],
        ]);

        // Split full name into first and last
        $nameParts = explode(' ', trim($data['name']), 2);
        $firstName = $nameParts[0] ?? $data['name'];
        $lastName  = $nameParts[1] ?? '';

        $doctor = Doctor::create([
            'user_id'        => null,
            'first_name'     => $firstName,
            'last_name'      => $lastName,
            'specialization' => $data['specialization'],
            'phone'          => $data['phone'] ?? null,
        ]);

        if (!empty($data['schedule']) && is_array($data['schedule'])) {
            foreach ($data['schedule'] as $day) {
                Schedule::create([
                    'doctor_id'          => $doctor->id,
                    'day_of_week'        => strtolower((string) $day),
                    'start_time'         => '08:00:00',
                    'end_time'           => '17:00:00',
                    'slot_duration_mins' => 30,
                    'is_available'       => true,
                ]);
            }
        }

        return redirect()->route('admin.doctors')->with('success', 'Doctor added successfully.');
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────

    public function edit(Doctor $doctor)
    {
        $doctor->load('schedules');
        return view('admin.doctor_edit', compact('doctor'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'first_name'     => ['required', 'string', 'max:100'],
            'last_name'      => ['required', 'string', 'max:100'],
            'specialization' => ['required', 'string', 'max:150'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'schedules'                      => ['nullable', 'array'],
            'schedules.*.day_of_week'        => ['required_with:schedules', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'schedules.*.start_time'         => ['required_with:schedules', 'date_format:H:i'],
            'schedules.*.end_time'           => ['required_with:schedules', 'date_format:H:i'],
            'schedules.*.slot_duration_mins' => ['nullable', 'integer', 'min:15'],
        ]);

        $doctor->update([
            'first_name'     => $data['first_name'],
            'last_name'      => $data['last_name'],
            'specialization' => $data['specialization'],
            'phone'          => $data['phone'] ?? null,
        ]);

        // Use $doctor->name accessor (already has "Dr.") — no manual prefix needed
        if ($doctor->user) {
            $doctor->user->update([
                'fullname' => $doctor->name, // accessor returns "Dr. FirstName LastName"
            ]);
        }

        // Replace schedules
        $doctor->schedules()->delete();

        if (!empty($data['schedules']) && is_array($data['schedules'])) {
            foreach ($data['schedules'] as $sched) {
                if (empty($sched['day_of_week'])) continue;

                Schedule::create([
                    'doctor_id'          => $doctor->id,
                    'day_of_week'        => strtolower((string) $sched['day_of_week']),
                    'start_time'         => $sched['start_time'] . ':00',
                    'end_time'           => $sched['end_time'] . ':00',
                    'slot_duration_mins' => (int) ($sched['slot_duration_mins'] ?? 30),
                    'is_available'       => true,
                ]);
            }
        }

        return redirect()->route('admin.doctors')->with('success', 'Doctor updated successfully.');
    }

    // ─── Deactivate ───────────────────────────────────────────────────────────

    public function deactivate(Doctor $doctor)
    {
        $doctor->schedules()->update(['is_available' => false]);
        return back()->with('success', "{$doctor->name} has been deactivated.");
    }

    // ─── Activate ────────────────────────────────────────────────────────────

    public function activate(Doctor $doctor)
    {
        $doctor->schedules()->update(['is_available' => true]);
        return back()->with('success', "{$doctor->name} has been activated.");
    }
}