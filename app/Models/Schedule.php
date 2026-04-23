<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'doctor_id',
        'day_of_week',        // 'monday' | 'tuesday' | ... | 'sunday'
        'start_time',         // e.g. '08:00:00'
        'end_time',           // e.g. '17:00:00'
        'slot_duration_mins', // default 30
        'is_available',       // boolean
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Generate all time slots for this schedule.
     * Returns array of time strings like ['08:00 AM', '08:30 AM', ...]
     */
    public function generateTimeSlots(): array
    {
        $slots    = [];
        $current  = \Carbon\Carbon::parse($this->start_time);
        $end      = \Carbon\Carbon::parse($this->end_time);
        $duration = (int) $this->slot_duration_mins;

        while ($current->lt($end)) {
            $slots[]  = $current->format('h:i A');
            $current->addMinutes($duration);
        }

        return $slots;
    }
}