<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientProfileController extends Controller
{
    public function show()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            abort(403, 'No patient profile found.');
        }

        return view('patient.profile', compact('patient'));
    }

    public function update(Request $request)
    {
        $patient = Auth::user()->patient;

        $data = $request->validate([
            'phone'         => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'gender'        => ['nullable', 'in:male,female,other'],
            'address'       => ['nullable', 'string', 'max:255'],
        ]);

        $patient->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }
}