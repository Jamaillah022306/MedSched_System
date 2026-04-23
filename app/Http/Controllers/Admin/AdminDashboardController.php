<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $appointmentsToday = Appointment::today()->count();
        $pendingCount      = Appointment::pending()->count();
        $totalPatients     = Patient::count();

        $query = Appointment::with(['patient', 'doctor'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%");
            });
        }

        $recentAppointments = $query->paginate(5);

        return view('admin.dashboard', compact(
            'appointmentsToday',
            'pendingCount',
            'totalPatients',
            'recentAppointments'
        ));
    }
}