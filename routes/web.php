<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminDoctorController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Nurse\NurseDashboardController;
use App\Http\Controllers\Nurse\NurseAppointmentController;
use App\Http\Controllers\Patient\PatientDashboardController;
use App\Http\Controllers\Patient\PatientAppointmentController;
use App\Http\Controllers\Patient\PatientProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password as PasswordRule;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', function () {
        return view('Auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Password reset link sent to your email!')
            : back()->withErrors(['email' => __($status)]);
    })->name('password.email');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('Auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password_hash' => Hash::make($password)])->save();
            }
        );
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password reset successfully!')
            : back()->withErrors(['email' => __($status)]);
    })->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        // Appointments — archived must be BEFORE {appointment} wildcard
        Route::get('/appointments/archived',                     [AdminAppointmentController::class, 'archived'])->name('admin.appointments.archived');
        Route::get('/appointments',                              [AdminAppointmentController::class, 'index'])   ->name('admin.appointments');
        Route::get('/appointments/{appointment}',                [AdminAppointmentController::class, 'show'])    ->name('admin.appointments.show');
        Route::patch('/appointments/{appointment}/confirm',      [AdminAppointmentController::class, 'confirm']) ->name('admin.appointments.confirm');
        Route::patch('/appointments/{appointment}/cancel',       [AdminAppointmentController::class, 'cancel'])  ->name('admin.appointments.cancel');
        Route::patch('/appointments/{appointment}/archive',      [AdminAppointmentController::class, 'archive']) ->name('admin.appointments.archive'); // ← BAG-O
        Route::delete('/appointments/{appointment}',             [AdminAppointmentController::class, 'destroy']) ->name('admin.appointments.destroy');

        // Doctors
        Route::get('/doctors',                       [AdminDoctorController::class, 'index'])      ->name('admin.doctors');
        Route::post('/doctors',                      [AdminDoctorController::class, 'store'])      ->name('admin.doctors.store');
        Route::get('/doctors/{doctor}/edit',         [AdminDoctorController::class, 'edit'])       ->name('admin.doctors.edit');
        Route::put('/doctors/{doctor}',              [AdminDoctorController::class, 'update'])     ->name('admin.doctors.update');
        Route::patch('/doctors/{doctor}/deactivate', [AdminDoctorController::class, 'deactivate'])->name('admin.doctors.deactivate');
        Route::patch('/doctors/{doctor}/activate',   [AdminDoctorController::class, 'activate'])  ->name('admin.doctors.activate');

        // Reports
        Route::get('/reports',        [AdminReportController::class, 'index']) ->name('admin.reports');
        Route::get('/reports/export', [AdminReportController::class, 'export'])->name('admin.reports.export');
    });

/*
|--------------------------------------------------------------------------
| Nurse Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:nurse'])
    ->prefix('nurse')
    ->group(function () {

        Route::get('/dashboard', [NurseDashboardController::class, 'index'])
            ->name('nurse.dashboard');

        Route::get('/appointments',                          [NurseAppointmentController::class, 'index'])   ->name('nurse.appointments');
        Route::get('/appointments/{appointment}',            [NurseAppointmentController::class, 'show'])    ->name('nurse.appointments.show');
        Route::patch('/appointments/{appointment}/confirm',  [NurseAppointmentController::class, 'confirm']) ->name('nurse.appointments.confirm');
        Route::patch('/appointments/{appointment}/done',     [NurseAppointmentController::class, 'done'])    ->name('nurse.appointments.done');
        Route::patch('/appointments/{appointment}/cancel',   [NurseAppointmentController::class, 'cancel'])  ->name('nurse.appointments.cancel');
        Route::patch('/appointments/{appointment}/archive',  [NurseAppointmentController::class, 'archive']) ->name('nurse.appointments.archive');
        Route::delete('/appointments/{appointment}',         [NurseAppointmentController::class, 'destroy']) ->name('nurse.appointments.destroy');
    });

/*
|--------------------------------------------------------------------------
| Patient Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:patient'])
    ->prefix('patient')
    ->group(function () {

        Route::get('/dashboard',                       [PatientDashboardController::class,   'index'])    ->name('patient.dashboard');
        Route::get('/appointments',                    [PatientAppointmentController::class, 'index'])    ->name('patient.appointments');
        Route::get('/book',                            [PatientAppointmentController::class, 'create'])   ->name('patient.book');
        Route::post('/book',                           [PatientAppointmentController::class, 'store'])    ->name('patient.book.store');
        Route::get('/appointments/{appointment}/slip', [PatientAppointmentController::class, 'slip'])     ->name('patient.appointments.slip');
        Route::get('/profile',                         [PatientProfileController::class,     'show'])     ->name('patient.profile');
        Route::put('/profile',                         [PatientProfileController::class,     'update'])   ->name('patient.profile.update');
    });