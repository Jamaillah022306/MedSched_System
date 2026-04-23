<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'appointment_date',
        'appointment_time',
        'type',
        'status',
        'reason',
        'cancel_reason',
        'acted_by',
        'queue_number',
        'is_archived',
        'archived_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'is_archived'      => 'boolean',
        'archived_at'      => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }

    public function actedBy()
    {
        return $this->belongsTo(User::class, 'acted_by', 'user_id');
    }

    // ─── Scopes ───────────────────────────────────────────────
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed'])
                     ->whereDate('appointment_date', '>=', today());
    }

    // ─── Helpers ──────────────────────────────────────────────
    public function isPending(): bool   { return $this->status === 'pending';   }
    public function isConfirmed(): bool { return $this->status === 'confirmed'; }
    public function isDone(): bool      { return $this->status === 'done';      }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }
}