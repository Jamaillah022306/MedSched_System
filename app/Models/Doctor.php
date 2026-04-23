<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    public $timestamps = false; // only has created_at

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'specialization',
        'phone',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'doctor_id', 'id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'id');
    }

    // ─── Accessor ─────────────────────────────────────────────────────────────

    /**
     * Full name  →  $doctor->name
     */
    public function getNameAttribute(): string
    {
        return "Dr. {$this->first_name} {$this->last_name}";
    }

    /**
     * Get available days  →  ['monday', 'wednesday', 'friday']
     */
    public function getAvailableDaysAttribute(): array
    {
        return $this->schedules
            ->where('is_available', true)
            ->pluck('day_of_week')
            ->toArray();
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Check if doctor is available on a given day (lowercase).
     */
    public function isAvailableOn(string $day): bool
    {
        return $this->schedules()
            ->where('day_of_week', strtolower($day))
            ->where('is_available', true)
            ->exists();
    }
}