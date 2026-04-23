<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportController extends Controller
{
    public function index()
    {
        $totalAppointments = Appointment::count();
        $totalPatients     = Patient::count();
        $done              = Appointment::where('status', 'done')->count();
        $cancelled         = Appointment::where('status', 'cancelled')->count();
        $pending           = Appointment::where('status', 'pending')->count();

        $appointments = Appointment::with(['patient', 'doctor'])->latest()->paginate(5);

        $patients = Patient::with(['user'])
            ->select('patients.*')
            ->selectSub(
                Appointment::selectRaw('COUNT(*)')
                    ->whereColumn('appointments.patient_id', 'patients.id'),
                'total_appointments_count'
            )
            ->selectSub(
                Appointment::selectRaw('COUNT(*)')
                    ->whereColumn('appointments.patient_id', 'patients.id')
                    ->where('status', 'done'),
                'done_count'
            )
            ->selectSub(
                Appointment::selectRaw('COUNT(*)')
                    ->whereColumn('appointments.patient_id', 'patients.id')
                    ->where('status', 'cancelled'),
                'cancelled_count'
            )
            ->paginate(5);

        return view('Admin.reports', compact(
            'totalAppointments', 'totalPatients',
            'done', 'cancelled', 'pending',
            'appointments', 'patients'
        ));
    }

    public function export()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->latest()->get();

        $patients = Patient::with(['user'])
            ->select('patients.*')
            ->selectSub(
                Appointment::selectRaw('COUNT(*)')
                    ->whereColumn('appointments.patient_id', 'patients.id'),
                'total_appointments_count'
            )
            ->selectSub(
                Appointment::selectRaw('COUNT(*)')
                    ->whereColumn('appointments.patient_id', 'patients.id')
                    ->where('status', 'done'),
                'done_count'
            )
            ->selectSub(
                Appointment::selectRaw('COUNT(*)')
                    ->whereColumn('appointments.patient_id', 'patients.id')
                    ->where('status', 'cancelled'),
                'cancelled_count'
            )
            ->get();

        $totalAppointments = $appointments->count();
        $totalPatients     = $patients->count();
        $done              = $appointments->where('status', 'done')->count();
        $cancelled         = $appointments->where('status', 'cancelled')->count();
        $pending           = $appointments->where('status', 'pending')->count();

        $pdf = Pdf::loadView('Admin.reports-pdf', compact(
            'appointments', 'patients',
            'totalAppointments', 'totalPatients',
            'done', 'cancelled', 'pending'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('appointments_report_' . now()->timezone('Asia/Manila')->format('Y-m-d') . '.pdf');
    }
}