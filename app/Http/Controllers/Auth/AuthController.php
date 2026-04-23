<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ─── Show Login ───────────────────────────────────────────────────────────

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    // ─── Login ────────────────────────────────────────────────────────────────

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Manual auth since column is password_hash not password
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    // ─── Show Register ────────────────────────────────────────────────────────

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.register');
    }

    // ─── Register (Patient only) ──────────────────────────────────────────────

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name'            => ['required', 'string', 'max:100'],
            'last_name'             => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::min(8)],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'date_of_birth'         => ['nullable', 'date'],
            'gender'                => ['nullable', 'in:male,female,other'],
            'address'               => ['nullable', 'string'],
        ]);

        // 1. Create user account
        $user = User::create([
            'fullname'      => "{$data['first_name']} {$data['last_name']}",
            'email'         => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'role'          => 'patient',
        ]);

        // 2. Create patient profile
        Patient::create([
            'user_id'       => $user->user_id,
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'],
            'phone'         => $data['phone'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender'        => $data['gender'] ?? null,
            'address'       => $data['address'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('patient.dashboard');
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ─── Helper ───────────────────────────────────────────────────────────────

    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'nurse'  => redirect()->route('nurse.dashboard'),
            'patient' => redirect()->route('patient.dashboard'),
            default  => redirect()->route('patient.dashboard'),
        };
    }
}