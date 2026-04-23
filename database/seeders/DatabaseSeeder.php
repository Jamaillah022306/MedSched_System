<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ───────────────────────────────────────────
        DB::table('users')->insert([
            'fullname'      => 'Jamaillah Santi',
            'email'         => 'admin@gmail.com',
            'password_hash' => Hash::make('jammy2323'),
            'role'          => 'admin',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // ─── Nurses ──────────────────────────────────────────
        $nurses = [
            ['fullname' => 'Sofia Smith',    'email' => 'nurse1@gmail.com'],
            ['fullname' => 'Evon Cajes',     'email' => 'nurse2@gmail.com'],
            ['fullname' => 'Rosa Dela Cruz', 'email' => 'nurse3@gmail.com'],
            ['fullname' => 'Liza Torres',    'email' => 'nurse4@gmail.com'],
        ];

        foreach ($nurses as $nurse) {
            DB::table('users')->insert([
                'fullname'      => $nurse['fullname'],
                'email'         => $nurse['email'],
                'password_hash' => Hash::make('nurse123'),
                'role'          => 'nurse',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // ─── Patients ────────────────────────────────────────
        $patients = [
            ['fullname' => 'Paul Jemeniz',  'email' => 'patient1@gmail.com'],
            ['fullname' => 'Maria Reyes',   'email' => 'patient2@gmail.com'],
            ['fullname' => 'Pedro Garcia',  'email' => 'patient3@gmail.com'],
            ['fullname' => 'Ana Lopez',     'email' => 'patient4@gmail.com'],
            ['fullname' => 'Rosa Santos',   'email' => 'patient5@gmail.com'],
        ];

        foreach ($patients as $patient) {
            // ← insertGetId para makuha ang bag-ong user_id
            $userId = DB::table('users')->insertGetId([
                'fullname'      => $patient['fullname'],
                'email'         => $patient['email'],
                'password_hash' => Hash::make('patient123'),
                'role'          => 'patient',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // ← Create corresponding patient profile
            $nameParts = explode(' ', $patient['fullname'], 2);
            DB::table('patients')->insert([
                'user_id'    => $userId,
                'first_name' => $nameParts[0],
                'last_name'  => $nameParts[1] ?? '',
                'created_at' => now(),
            ]);
        }
    }
}